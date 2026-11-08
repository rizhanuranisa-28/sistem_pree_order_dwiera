<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\UkuranProduk;
use App\Models\JadwalPengambilan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Support\ValidasiJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    /**
     * Menampilkan halaman pembayaran.
     */
    public function create(Request $request, $id)
    {
        $produk = Produk::where('status', 'aktif')
            ->findOrFail($id);

        $ukuran = UkuranProduk::where('id_ukuran', $request->query('id_ukuran'))
            ->where('id_produk', $produk->id_produk)
            ->where('status', 'aktif')
            ->first();

        if (!$ukuran) {
            return redirect()
                ->route('preorder.create', $produk->id_produk)
                ->with('error', 'Silakan pilih ukuran kue yang tersedia.');
        }

        $jumlah = (int) $request->query('jumlah', 1);

        $tanggalPengambilan = $request->query('tanggal_pengambilan');
        $jamPengambilan = $request->query('jam_pengambilan');
        $catatan = $request->query('catatan');
        $metode = $request->query('metode');

        if ($jumlah < 1 || $jumlah > 100) {
            return redirect()
                ->route('preorder.create', $produk->id_produk)
                ->withErrors([
                    'jumlah' => 'Jumlah kue harus antara 1 sampai 100.'
                ])
                ->withInput();
        }

        // Cek jadwal sebelum menampilkan halaman pembayaran.
        if ($tanggalPengambilan && $jamPengambilan) {
            $nomorTelepon = Auth::user()->nomor_telepon ?? null;

            $errorJadwal = ValidasiJadwal::cek(
                $tanggalPengambilan,
                $jamPengambilan,
                null,
                $nomorTelepon
            );

            if ($errorJadwal) {
                return redirect()
                    ->route('preorder.create', $produk->id_produk)
                    ->withErrors([
                        'jam_pengambilan' => $errorJadwal
                    ])
                    ->withInput();
            }
        }

        $harga = (float) $ukuran->harga;
        $total = $harga * $jumlah;

        return view('pembayaran', compact(
            'produk',
            'ukuran',
            'jumlah',
            'tanggalPengambilan',
            'jamPengambilan',
            'catatan',
            'metode',
            'harga',
            'total'
        ));
    }

    /**
     * Menyimpan pesanan dan tagihan pembayaran.
     */
    public function konfirmasi(Request $request, $id)
    {
        $validated = $request->validate([
            'id_ukuran' => [
                'required',
                'integer',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
            'tanggal_pengambilan' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'jam_pengambilan' => [
                'required',
                'date_format:H:i',
            ],
            'metode' => [
                'required',
                'in:cash,transfer',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $produk = Produk::where('status', 'aktif')
            ->findOrFail($id);

        $ukuran = UkuranProduk::where('id_ukuran', $validated['id_ukuran'])
            ->where('id_produk', $produk->id_produk)
            ->where('status', 'aktif')
            ->first();

        if (!$ukuran) {
            return redirect()
                ->route('preorder.create', $produk->id_produk)
                ->with('error', 'Ukuran kue sudah tidak tersedia. Silakan pilih ukuran lain.');
        }

        $jumlah = (int) $validated['jumlah'];
        $tanggalPengambilan = $validated['tanggal_pengambilan'];
        $jamPengambilan = $validated['jam_pengambilan'];
        $metode = $validated['metode'];
        $catatan = $validated['catatan'] ?? null;

        $nomorTelepon = Auth::user()->nomor_telepon ?? null;

        /*
         * Kunci jadwal agar pemesanan pada slot yang sama
         * diproses secara bergantian.
         */
        $pesanan = ValidasiJadwal::denganKunci(
            $tanggalPengambilan,
            $jamPengambilan,
            function () use (
                $produk,
                $ukuran,
                $jumlah,
                $tanggalPengambilan,
                $jamPengambilan,
                $metode,
                $catatan,
                $nomorTelepon
            ) {
                return DB::transaction(function () use (
                    $produk,
                    $ukuran,
                    $jumlah,
                    $tanggalPengambilan,
                    $jamPengambilan,
                    $metode,
                    $catatan,
                    $nomorTelepon
                ) {
                    // Periksa ulang jadwal sebelum menyimpan.
                    $errorJadwal = ValidasiJadwal::cek(
                        $tanggalPengambilan,
                        $jamPengambilan,
                        null,
                        $nomorTelepon
                    );

                    if ($errorJadwal) {
                        throw ValidationException::withMessages([
                            'jam_pengambilan' => $errorJadwal,
                        ]);
                    }

                    // Periksa ulang produk dan ukuran dari database.
                    $produkAktif = Produk::where('status', 'aktif')
                        ->find($produk->id_produk);

                    $ukuranAktif = UkuranProduk::where(
                        'id_ukuran',
                        $ukuran->id_ukuran
                    )
                        ->where('id_produk', $produk->id_produk)
                        ->where('status', 'aktif')
                        ->first();

                    if (!$produkAktif || !$ukuranAktif) {
                        throw ValidationException::withMessages([
                            'id_ukuran' => 'Produk atau ukuran sudah tidak tersedia.',
                        ]);
                    }

                    // Harga selalu diambil dari database.
                    $harga = (float) $ukuranAktif->harga;
                    $total = $harga * $jumlah;

                    // Buat jadwal pengambilan.
                    $jadwal = JadwalPengambilan::create([
                        'tanggal_pengambilan' => $tanggalPengambilan,
                        'jam_pengambilan' => $jamPengambilan,
                        'status_pengambilan' => 'menunggu',
                    ]);

                    // Buat pesanan.
                    $pesanan = Pesanan::create([
                        'id_users' => Auth::user()->id_users,
                        'id_jadwal' => $jadwal->id_jadwal,
                        'tanggal_pesan' => now(),
                        'tanggal_pengambilan' => $tanggalPengambilan,
                        'total_harga' => $total,
                        'status_pesanan' => 'menunggu',
                        'catatan' => $catatan,
                    ]);

                    // Simpan detail beserta ukuran yang dipilih.
                    DetailPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_produk' => $produkAktif->id_produk,
                        'ukuran' => $ukuranAktif->nama_ukuran,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $harga,
                        'subtotal' => $total,
                    ]);

                    // Simpan tagihan pembayaran.
                    Pembayaran::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'tanggal_bayar' => null,
                        'metode_pembayaran' => $metode,
                        'jumlah_bayar' => 0,
                        'total_bayar' => $total,
                        'bayar' => 0,
                        'kembalian' => 0,
                    ]);

                    return $pesanan;
                });
            }
        );

        // Transfer: lanjut ke unggah bukti pembayaran.
        if ($metode === 'transfer') {
            return redirect()->route(
                'pesanan.bukti.form',
                $pesanan->id_pesanan
            );
        }

        // Cash: lanjut ke halaman pesanan berhasil.
        return redirect()->route(
            'pesanan.bukti.selesai',
            $pesanan->id_pesanan
        );
    }
}
