<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailPesanan;
use App\Models\JadwalPengambilan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\UkuranProduk;
use App\Support\ValidasiJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminTambahPesananController extends Controller
{
    /**
     * Menampilkan formulir tambah pesanan admin.
     */
    public function create()
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->where('status', 'aktif')
                    ->orderBy('harga', 'asc');
            }
        ])
        ->where('status', 'aktif')
        ->orderBy('nama_kue')
        ->get();

        return view('admin_tambah_pesanan', compact('produk'));
    }

    /**
     * Menyimpan pesanan customer yang datang langsung ke toko.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',

            'produk' => 'required|array|min:1',
            'produk.*.id_produk' => 'required|integer|exists:produk,id_produk',
            'produk.*.id_ukuran' => 'required|integer|exists:ukuran_produk,id_ukuran',
            'produk.*.jumlah' => 'required|integer|min:1|max:100',

            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'jam_pengambilan' => 'required|date_format:H:i',

            'catatan' => 'nullable|string|max:1000',

            'metode_pembayaran' => 'required|in:cash,transfer',
            'jumlah_bayar' => 'required_if:metode_pembayaran,cash|nullable|numeric|min:0',
        ]);

        /*
         * Kunci slot berdasarkan tanggal dan jam.
         * Pemesanan admin dan pelanggan menggunakan
         * pengunci yang sama.
         */
        $hasil = ValidasiJadwal::denganKunci(
            $validated['tanggal_pengambilan'],
            $validated['jam_pengambilan'],
            function () use ($validated) {

                return DB::transaction(function () use ($validated) {

                    /*
                     * Periksa ulang ketersediaan jadwal.
                     */
                    $errorJadwal = ValidasiJadwal::cek(
                        $validated['tanggal_pengambilan'],
                        $validated['jam_pengambilan'],
                        null,
                        $validated['nomor_telepon']
                    );

                    if ($errorJadwal) {
                        throw ValidationException::withMessages([
                            'jam_pengambilan' => $errorJadwal,
                        ]);
                    }

                    /*
                     * Cari customer berdasarkan nomor telepon.
                     */
                    $customer = Customer::where(
                        'nomor_telepon',
                        $validated['nomor_telepon']
                    )->first();

                    if ($customer) {
                        $customer->update([
                            'nama_customer' => $validated['nama_customer'],
                        ]);
                    } else {
                        $customer = Customer::create([
                            'nama_customer' => $validated['nama_customer'],
                            'nomor_telepon' => $validated['nomor_telepon'],
                        ]);
                    }

                    /*
                     * Ambil produk, ukuran, dan harga dari database.
                     */
                    $totalHarga = 0;
                    $detailProduk = [];

                    foreach ($validated['produk'] as $item) {

                        $produk = Produk::where(
                            'id_produk',
                            $item['id_produk']
                        )
                        ->where('status', 'aktif')
                        ->lockForUpdate()
                        ->first();

                        if (!$produk) {
                            throw ValidationException::withMessages([
                                'produk' => 'Produk tidak tersedia atau sudah tidak aktif.',
                            ]);
                        }

                        $ukuran = UkuranProduk::where(
                            'id_ukuran',
                            $item['id_ukuran']
                        )
                        ->where('id_produk', $produk->id_produk)
                        ->where('status', 'aktif')
                        ->first();

                        if (!$ukuran) {
                            throw ValidationException::withMessages([
                                'produk' => 'Ukuran yang dipilih tidak tersedia untuk produk tersebut.',
                            ]);
                        }

                        $jumlah = (int) $item['jumlah'];
                        $harga = (float) $ukuran->harga;
                        $subtotal = $harga * $jumlah;

                        $totalHarga += $subtotal;

                        $detailProduk[] = [
                            'id_produk' => $produk->id_produk,
                            'ukuran' => $ukuran->nama_ukuran,
                            'jumlah' => $jumlah,
                            'harga_satuan' => $harga,
                            'subtotal' => $subtotal,
                        ];
                    }

                    /*
                     * Periksa pembayaran cash.
                     */
                    $metode = $validated['metode_pembayaran'];

                    $uangDiterima = $metode === 'cash'
                        ? (float) $validated['jumlah_bayar']
                        : 0;

                    if (
                        $metode === 'cash' &&
                        $uangDiterima < $totalHarga
                    ) {
                        throw ValidationException::withMessages([
                            'jumlah_bayar' => 'Uang yang diterima harus sama dengan atau lebih besar dari total harga.',
                        ]);
                    }

                    /*
                     * Simpan jadwal pengambilan.
                     */
                    $jadwal = JadwalPengambilan::create([
                        'tanggal_pengambilan' => $validated['tanggal_pengambilan'],
                        'jam_pengambilan' => $validated['jam_pengambilan'],
                        'status_pengambilan' => 'menunggu',
                    ]);

                    /*
                     * Simpan pesanan offline.
                     */
                    $pesanan = Pesanan::create([
                        'id_users' => null,
                        'id_customer' => $customer->id_customer,
                        'id_jadwal' => $jadwal->id_jadwal,
                        'tanggal_pesan' => now(),
                        'tanggal_pengambilan' => $validated['tanggal_pengambilan'],
                        'total_harga' => $totalHarga,
                        'status_pesanan' => 'menunggu',
                        'catatan' => $validated['catatan'] ?? null,
                    ]);

                    /*
                     * Simpan detail pesanan beserta ukuran.
                     */
                    foreach ($detailProduk as $detail) {
                        DetailPesanan::create([
                            'id_pesanan' => $pesanan->id_pesanan,
                            'id_produk' => $detail['id_produk'],
                            'ukuran' => $detail['ukuran'],
                            'jumlah' => $detail['jumlah'],
                            'harga_satuan' => $detail['harga_satuan'],
                            'subtotal' => $detail['subtotal'],
                        ]);
                    }

                    /*
                     * Simpan pembayaran.
                     */
                    Pembayaran::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'tanggal_bayar' => $metode === 'cash'
                            ? now()
                            : null,
                        'metode_pembayaran' => $metode,
                        'jumlah_bayar' => $uangDiterima,
                        'total_bayar' => $totalHarga,
                        'bayar' => $metode === 'cash'
                            ? $totalHarga
                            : 0,
                        'kembalian' => $metode === 'cash'
                            ? $uangDiterima - $totalHarga
                            : 0,
                        'bukti_pembayaran' => null,
                        'status_bukti' => 'menunggu',
                        'alasan_pengembalian' => null,
                    ]);

                    return $pesanan;
                });
            }
        );

        return redirect()
            ->route('admin.pesanan')
            ->with(
                'success',
                'Pesanan berhasil ditambahkan. ID Pesanan: '
                . $hasil->id_pesanan
                . '. ID Customer: '
                . $hasil->id_customer
                . '.'
            );
    }
}