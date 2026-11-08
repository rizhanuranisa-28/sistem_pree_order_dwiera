<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Support\ValidasiJadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    /**
     * Menampilkan seluruh pesanan untuk endpoint JSON.
     */
    public function index()
    {
        $pesanan = Pesanan::with([
            'user',
            'detailPesanans',
            'pembayaran',
            'jadwalPengambilan',
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $pesanan,
        ]);
    }

    /**
     * Menampilkan pesanan milik pelanggan yang sedang login.
     */
    public function pelanggan()
    {
        $pesanan = Pesanan::with([
            'detailPesanans.produk',
            'pembayaran',
            'jadwalPengambilan',
        ])
            ->where('id_users', Auth::user()->id_users)
            ->orderByDesc('id_pesanan')
            ->get();

        return view('pesanan_pelanggan', compact('pesanan'));
    }

    /**
     * Menampilkan formulir edit pesanan.
     */
    public function edit($id)
    {
        $pesanan = Pesanan::with([
            'detailPesanans.produk',
            'jadwalPengambilan',
            'pembayaran',
        ])
            ->where('id_pesanan', $id)
            ->where('id_users', Auth::user()->id_users)
            ->firstOrFail();

        if ($pesanan->status_pesanan !== 'menunggu') {
            return redirect()
                ->route('pesanan.pelanggan')
                ->with('error', 'Pesanan tidak dapat diedit karena sudah diproses.');
        }

        if (
            $pesanan->pembayaran &&
            (float) $pesanan->pembayaran->jumlah_bayar > 0
        ) {
            return redirect()
                ->route('pesanan.pelanggan')
                ->with('error', 'Pembayaran sudah tercatat. Hubungi admin untuk mengubah pesanan.');
        }

        $produk = Produk::where('status', 'aktif')
            ->orderBy('nama_kue')
            ->get();

        return view('pesanan_edit', compact('pesanan', 'produk'));
    }

    /**
     * Memperbarui pesanan yang masih menunggu.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_produk' => ['required', 'integer', 'exists:produk,id_produk'],
            'items.*.jumlah' => ['required', 'integer', 'min:1', 'max:100'],
            'tanggal_pengambilan' => ['required', 'date'],
            'jam_pengambilan' => ['required', 'date_format:H:i'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'items.required' => 'Pilih minimal 1 kue.',
            'items.min' => 'Pilih minimal 1 kue.',
            'items.*.id_produk.required' => 'Setiap baris harus memilih kue.',
            'items.*.jumlah.required' => 'Jumlah kue wajib diisi.',
            'items.*.jumlah.min' => 'Jumlah kue minimal 1.',
            'items.*.jumlah.max' => 'Jumlah kue maksimal 100.',
            'jam_pengambilan.date_format' => 'Format jam pengambilan tidak valid.',
        ]);

        // Satu jenis kue tidak boleh muncul di dua baris.
        $idProdukDipilih = array_map(
            fn ($item) => (int) $item['id_produk'],
            $validated['items']
        );

        if (
            count($idProdukDipilih) !==
            count(array_unique($idProdukDipilih))
        ) {
            throw ValidationException::withMessages([
                'items' => 'Jenis kue yang sama tidak boleh dipilih dua kali. Naikkan jumlahnya saja.',
            ]);
        }

        $idUser = Auth::user()->id_users;
        $nomorTelepon = Auth::user()->nomor_telepon ?? null;

        $tanggal = $validated['tanggal_pengambilan'];
        $jam = $validated['jam_pengambilan'];

        /*
         * Kunci slot berdasarkan tanggal dan jam baru.
         * Pemesanan lain pada slot tersebut harus menunggu
         * sampai proses edit selesai.
         */
        ValidasiJadwal::denganKunci(
            $tanggal,
            $jam,
            function () use (
                $validated,
                $id,
                $idUser,
                $idProdukDipilih,
                $nomorTelepon,
                $tanggal,
                $jam
            ) {

                DB::transaction(function () use (
                    $validated,
                    $id,
                    $idUser,
                    $idProdukDipilih,
                    $nomorTelepon,
                    $tanggal,
                    $jam
                ) {

                    $pesanan = Pesanan::where('id_pesanan', $id)
                        ->where('id_users', $idUser)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($pesanan->status_pesanan !== 'menunggu') {
                        throw ValidationException::withMessages([
                            'pesanan' => 'Pesanan sudah diproses dan tidak dapat diedit.',
                        ]);
                    }

                    $jadwal = $pesanan->jadwalPengambilan;

                    if (!$jadwal) {
                        throw ValidationException::withMessages([
                            'pesanan' => 'Jadwal pengambilan tidak ditemukan.',
                        ]);
                    }

                    $pembayaran = $pesanan->pembayaran;

                    if (!$pembayaran) {
                        throw ValidationException::withMessages([
                            'pesanan' => 'Data pembayaran tidak ditemukan.',
                        ]);
                    }

                    if ((float) $pembayaran->jumlah_bayar > 0) {
                        throw ValidationException::withMessages([
                            'pesanan' => 'Pembayaran sudah tercatat. Hubungi admin untuk mengubah pesanan.',
                        ]);
                    }

                    $tanggalLama = Carbon::parse(
                        $jadwal->tanggal_pengambilan
                    )->format('Y-m-d');

                    $jamLama = substr(
                        (string) $jadwal->jam_pengambilan,
                        0,
                        5
                    );

                    /*
                     * Validasi jadwal baru.
                     * Jadwal milik pesanan sendiri dikecualikan.
                     *
                     * Validasi tetap dijalankan meskipun tanggal
                     * dan jam tidak berubah agar aturan jadwal
                     * tetap diperiksa.
                     */
                    $errorJadwal = ValidasiJadwal::cek(
                        $tanggal,
                        $jam,
                        (int) $jadwal->id_jadwal,
                        $nomorTelepon
                    );

                    if ($errorJadwal) {
                        throw ValidationException::withMessages([
                            'jam_pengambilan' => $errorJadwal,
                        ]);
                    }

                    // Semua kue harus berstatus aktif.
                    $produkList = Produk::where('status', 'aktif')
                        ->whereIn('id_produk', $idProdukDipilih)
                        ->get()
                        ->keyBy('id_produk');

                    if (
                        $produkList->count() !==
                        count($idProdukDipilih)
                    ) {
                        throw ValidationException::withMessages([
                            'items' => 'Ada kue yang tidak tersedia lagi. Silakan pilih kue lain.',
                        ]);
                    }

                    // Ganti seluruh detail dengan pilihan baru.
                    $pesanan->detailPesanans()->delete();

                    $total = 0;

                    foreach ($validated['items'] as $item) {
                        $produk = $produkList[
                            (int) $item['id_produk']
                        ];

                        $jumlah = (int) $item['jumlah'];
                        $harga = (float) $produk->harga;
                        $subtotal = round($harga * $jumlah, 2);

                        DetailPesanan::create([
                            'id_pesanan' => $pesanan->id_pesanan,
                            'id_produk' => $produk->id_produk,
                            'jumlah' => $jumlah,
                            'harga_satuan' => $harga,
                            'subtotal' => $subtotal,
                        ]);

                        $total += $subtotal;
                    }

                    // Perbarui data pesanan.
                    $pesanan->update([
                        'tanggal_pengambilan' => $tanggal,
                        'total_harga' => $total,
                        'catatan' => $validated['catatan'] ?? null,
                    ]);

                    // Perbarui jadwal pengambilan.
                    $jadwal->update([
                        'tanggal_pengambilan' => $tanggal,
                        'jam_pengambilan' => $jam,
                    ]);

                    // Perbarui total pembayaran.
                    $pembayaran->update([
                        'total_bayar' => $total,
                    ]);
                });
            }
        );

        return redirect()
            ->route('pesanan.pelanggan')
            ->with(
                'success',
                'Pesanan berhasil diperbarui. Perubahan dapat diperiksa admin sebelum diproses.'
            );
    }

    /**
     * Membatalkan pesanan milik pelanggan yang masih menunggu.
     */
    public function batal($id)
    {
        $idUser = Auth::user()->id_users;

        DB::transaction(function () use ($id, $idUser) {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->where('id_users', $idUser)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pesanan->status_pesanan !== 'menunggu') {
                throw ValidationException::withMessages([
                    'pesanan' => 'Pesanan tidak dapat dibatalkan karena sudah diproses.',
                ]);
            }

            $jadwal = $pesanan->jadwalPengambilan;
            $pembayaran = $pesanan->pembayaran;

            if (
                $pembayaran &&
                (float) $pembayaran->jumlah_bayar > 0
            ) {
                throw ValidationException::withMessages([
                    'pesanan' => 'Pembayaran sudah tercatat. Hubungi admin untuk pembatalan.',
                ]);
            }

            $pesanan->detailPesanans()->delete();
            $pesanan->pembayaran()->delete();
            $pesanan->delete();

            if ($jadwal && !$jadwal->pesanan()->exists()) {
                $jadwal->delete();
            }
        });

        return redirect()
            ->route('pesanan.pelanggan')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}