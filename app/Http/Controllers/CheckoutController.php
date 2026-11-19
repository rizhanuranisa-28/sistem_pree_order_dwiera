<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\UkuranProduk;
use App\Models\JadwalPengambilan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Support\BatasJumlahPesanan;
use App\Support\ValidasiJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Checkout satu jenis kue.
     */
    public function create(Request $request, $id)
    {
        $produk = Produk::whereIn('status', ['aktif', 'tersedia'])
            ->findOrFail($id);

        // Ambil ukuran yang dipilih pelanggan.
        $ukuran = UkuranProduk::where('id_ukuran', $request->query('id_ukuran'))
            ->where('id_produk', $produk->id_produk)
            ->whereIn('status', ['aktif', 'tersedia'])
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

        if ($jumlah < 1 || $jumlah > BatasJumlahPesanan::MAKSIMAL_KUE) {
            return redirect()
                ->route('preorder.create', $produk->id_produk)
                ->withErrors([
                    'jumlah' => 'Jumlah kue harus antara 1 sampai 2.'
                ])
                ->withInput();
        }

        // Validasi awal jadwal.
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

        // Harga diambil dari ukuran yang dipilih, bukan dari input pelanggan.
        $harga = (float) $ukuran->harga;
        $total = $harga * $jumlah;

        return view('checkout', compact(
            'produk',
            'ukuran',
            'jumlah',
            'tanggalPengambilan',
            'jamPengambilan',
            'catatan',
            'harga',
            'total'
        ));
    }

    /**
     * Menampilkan checkout beberapa jenis kue dari keranjang.
     */
    public function keranjang()
    {
        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        if (BatasJumlahPesanan::total($keranjang) > BatasJumlahPesanan::MAKSIMAL_KUE) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', BatasJumlahPesanan::pesanMaksimal());
        }

        $items = [];
        $total = 0;

        foreach ($keranjang as $key => $data) {
            $produk = Produk::whereIn('status', ['aktif', 'tersedia'])
                ->find($data['id_produk'] ?? null);

            $ukuran = UkuranProduk::where('id_ukuran', $data['id_ukuran'] ?? null)
                ->where('id_produk', $data['id_produk'] ?? null)
                ->whereIn('status', ['aktif', 'tersedia'])
                ->first();

            if (!$produk || !$ukuran) {
                continue;
            }

            $jumlah = (int) ($data['jumlah'] ?? 1);

            if ($jumlah < 1 || $jumlah > BatasJumlahPesanan::MAKSIMAL_KUE) {
                return redirect()
                    ->route('keranjang.index')
                    ->with('error', 'Jumlah salah satu produk tidak valid.');
            }

            $harga = (float) $ukuran->harga;
            $subtotal = $harga * $jumlah;

            $items[] = [
                'key' => $key,
                'produk' => $produk,
                'ukuran' => $ukuran,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        if (empty($items)) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Produk atau ukuran di keranjang sudah tidak tersedia. Silakan periksa kembali keranjang kamu.');
        }

        $sekarang = now(config('app.timezone', 'Asia/Jakarta'));
        $batasMinimalJadwal = ValidasiJadwal::batasMinimal($sekarang);
        $waktuPalingCepat = ValidasiJadwal::waktuPalingCepat($sekarang);
        $pesanJadwalMinimum = ValidasiJadwal::pesanJadwalMinimum($sekarang);

        return view('checkout-keranjang', compact(
            'items',
            'total',
            'batasMinimalJadwal',
            'waktuPalingCepat',
            'pesanJadwalMinimum'
        ));
    }

    /**
     * Menyimpan satu pesanan yang memiliki beberapa detail kue.
     */
    public function prosesKeranjang(Request $request)
    {
        $validated = $request->validate([
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
                'in:cash,tunai,transfer,qris',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        if (BatasJumlahPesanan::total($keranjang) > BatasJumlahPesanan::MAKSIMAL_KUE) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', BatasJumlahPesanan::pesanMaksimal());
        }

        $tanggalPengambilan = $validated['tanggal_pengambilan'];
        $jamPengambilan = $validated['jam_pengambilan'];
        $metode = $validated['metode'] === 'tunai'
            ? 'cash'
            : $validated['metode'];
        $catatan = $validated['catatan'] ?? null;

        $nomorTelepon = Auth::user()->nomor_telepon ?? null;

        /*
         * Kunci slot terlebih dahulu.
         * Pemesanan pada tanggal dan jam yang sama
         * akan diproses secara bergantian.
         */
        $pesanan = ValidasiJadwal::denganKunci(
            $tanggalPengambilan,
            $jamPengambilan,
            function () use (
                $keranjang,
                $tanggalPengambilan,
                $jamPengambilan,
                $metode,
                $catatan,
                $nomorTelepon
            ) {
                return DB::transaction(function () use (
                    $keranjang,
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

                    $detail = [];
                    $total = 0;

                    foreach ($keranjang as $data) {
                        $idProduk = $data['id_produk'] ?? null;
                        $idUkuran = $data['id_ukuran'] ?? null;

                        $produk = Produk::whereIn('status', ['aktif', 'tersedia'])
                            ->find($idProduk);

                        $ukuran = UkuranProduk::where('id_ukuran', $idUkuran)
                            ->where('id_produk', $idProduk)
                            ->whereIn('status', ['aktif', 'tersedia'])
                            ->first();

                        if (!$produk || !$ukuran) {
                            throw ValidationException::withMessages([
                                'keranjang' => 'Ada produk atau ukuran yang sudah tidak tersedia. Silakan periksa kembali keranjang kamu.',
                            ]);
                        }

                        $jumlah = (int) ($data['jumlah'] ?? 1);

                        if ($jumlah < 1 || $jumlah > BatasJumlahPesanan::MAKSIMAL_KUE) {
                            throw ValidationException::withMessages([
                                'jumlah' => 'Jumlah kue harus antara 1 sampai 2.',
                            ]);
                        }

                        // Gunakan harga ukuran dari database.
                        $harga = (float) $ukuran->harga;
                        $subtotal = $harga * $jumlah;

                        $detail[] = [
                            'id_produk' => $produk->id_produk,
                            'ukuran' => $ukuran->nama_ukuran,
                            'jumlah' => $jumlah,
                            'harga_satuan' => $harga,
                            'subtotal' => $subtotal,
                        ];

                        $total += $subtotal;
                    }

                    if (empty($detail)) {
                        throw ValidationException::withMessages([
                            'keranjang' => 'Tidak ada produk yang bisa dipesan.',
                        ]);
                    }

                    // Satu jadwal untuk seluruh jenis kue.
                    $jadwal = JadwalPengambilan::create([
                        'tanggal_pengambilan' => $tanggalPengambilan,
                        'jam_pengambilan' => $jamPengambilan,
                        'status_pengambilan' => 'menunggu',
                    ]);

                    // Satu pesanan untuk seluruh jenis kue.
                    $pesanan = Pesanan::create([
                        'id_users' => Auth::user()->id_users,
                        'id_jadwal' => $jadwal->id_jadwal,
                        'tanggal_pesan' => now(),
                        'tanggal_pengambilan' => $tanggalPengambilan,
                        'total_harga' => $total,
                        'status_pesanan' => 'menunggu',
                        'catatan' => $catatan,
                    ]);

                    // Simpan setiap detail produk beserta ukuran pilihannya.
                    foreach ($detail as $item) {
                        DetailPesanan::create([
                            'id_pesanan' => $pesanan->id_pesanan,
                            'id_produk' => $item['id_produk'],
                            'ukuran' => $item['ukuran'],
                            'jumlah' => $item['jumlah'],
                            'harga_satuan' => $item['harga_satuan'],
                            'subtotal' => $item['subtotal'],
                        ]);
                    }

                    // Simpan pembayaran.
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

        // Kosongkan keranjang setelah transaksi berhasil.
        session()->forget('keranjang');

        if (in_array($metode, ['transfer', 'qris'], true)) {
            return redirect()->route(
                'pesanan.bukti.form',
                $pesanan->id_pesanan
            );
        }

        return redirect()->route(
            'pesanan.bukti.selesai',
            $pesanan->id_pesanan
        );
    }
}
