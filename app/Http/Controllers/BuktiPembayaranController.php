<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\RiwayatBuktiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuktiPembayaranController extends Controller
{
    /**
     * Halaman unggah bukti transfer.
     */
    public function form($id)
    {
        $pesanan = Pesanan::with([
            'pembayaran',
            'detailPesanans.produk'
        ])
            ->where('id_pesanan', $id)
            ->where('id_users', Auth::user()->id_users)
            ->firstOrFail();

        $pembayaran = $pesanan->pembayaran;

        if (
            !$pembayaran ||
            strtolower((string) $pembayaran->metode_pembayaran) !== 'transfer'
        ) {
            return redirect()->route(
                'pesanan.bukti.selesai',
                $pesanan->id_pesanan
            );
        }

        if (!empty($pembayaran->tanggal_bayar)) {
            return redirect()->route('pesanan.pelanggan');
        }

        return view('bukti_transfer', compact('pesanan'));
    }

    /**
     * Halaman konfirmasi setelah bukti diunggah.
     */
    public function selesai($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_users', Auth::user()->id_users)
            ->firstOrFail();

        return view('konfirmasi_pembayaran', compact('pesanan'));
    }

    /**
     * Pelanggan mengunggah atau memperbaiki bukti transfer.
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ], [
            'bukti.required' => 'Pilih foto bukti transfer terlebih dahulu.',
            'bukti.image' => 'File harus berupa gambar.',
            'bukti.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'bukti.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_pesanan', $id)
            ->where('id_users', Auth::user()->id_users)
            ->firstOrFail();

        $pembayaran = $pesanan->pembayaran;

        if (
            !$pembayaran ||
            strtolower((string) $pembayaran->metode_pembayaran) !== 'transfer'
        ) {
            return redirect()
                ->route('pesanan.pelanggan')
                ->with(
                    'error',
                    'Bukti pembayaran hanya untuk pesanan dengan metode transfer.'
                );
        }

        if (!empty($pembayaran->tanggal_bayar)) {
            return redirect()
                ->route('pesanan.pelanggan')
                ->with(
                    'error',
                    'Pembayaran sudah dikonfirmasi admin, bukti tidak dapat diubah.'
                );
        }

        $file = $request->file('bukti');

        // Nama unik supaya file lama tidak tertimpa.
        $namaFile = 'bukti_'
            . $pesanan->id_pesanan
            . '_'
            . uniqid()
            . '.'
            . $file->extension();

        $folderBukti = public_path('images/bukti');

        // Pastikan folder penyimpanan tersedia.
        if (!is_dir($folderBukti)) {
            mkdir($folderBukti, 0755, true);
        }

        // Simpan file baru tanpa menghapus file bukti sebelumnya.
        $file->move($folderBukti, $namaFile);

        try {
            DB::transaction(function () use ($pembayaran, $namaFile) {

                $data = $pembayaran->newQuery()
                    ->where('id_pembayaran', $pembayaran->id_pembayaran)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Jangan izinkan perubahan jika pembayaran sudah lunas.
                if (!empty($data->tanggal_bayar)) {
                    throw new \RuntimeException(
                        'Pembayaran sudah dikonfirmasi admin.'
                    );
                }

                // Simpan bukti sebelumnya ke riwayat jika ada.
                if (!empty($data->bukti_pembayaran)) {
                    $riwayatLama = RiwayatBuktiPembayaran::where(
                        'id_pembayaran',
                        $data->id_pembayaran
                    )
                        ->where('nama_file', $data->bukti_pembayaran)
                        ->first();

                    if (!$riwayatLama) {
                        RiwayatBuktiPembayaran::create([
                            'id_pembayaran' => $data->id_pembayaran,
                            'nama_file' => $data->bukti_pembayaran,
                            'alasan_pengembalian' => $data->alasan_pengembalian,
                            'tanggal_upload' => now(),
                            'status' => $data->status_bukti ?? 'menunggu',
                        ]);
                    }
                }

                // Simpan bukti terbaru dan reset status pemeriksaan.
                $data->update([
                    'bukti_pembayaran' => $namaFile,
                    'status_bukti' => 'menunggu',
                    'alasan_pengembalian' => null,
                ]);

                // Catat bukti terbaru di tabel riwayat.
                RiwayatBuktiPembayaran::create([
                    'id_pembayaran' => $data->id_pembayaran,
                    'nama_file' => $namaFile,
                    'alasan_pengembalian' => null,
                    'tanggal_upload' => now(),
                    'status' => 'menunggu',
                ]);
            });
        } catch (\Throwable $e) {
            // Hapus hanya file baru jika penyimpanan database gagal.
            if (is_file($folderBukti . DIRECTORY_SEPARATOR . $namaFile)) {
                unlink($folderBukti . DIRECTORY_SEPARATOR . $namaFile);
            }

            if ($e instanceof \RuntimeException) {
                return redirect()
                    ->route('pesanan.pelanggan')
                    ->with('error', $e->getMessage());
            }

            report($e);

            return redirect()
                ->route('pesanan.pelanggan')
                ->with(
                    'error',
                    'Bukti gagal disimpan. Silakan coba lagi.'
                );
        }

        if ($request->boolean('lanjut')) {
            return redirect()->route(
                'pesanan.bukti.selesai',
                $pesanan->id_pesanan
            );
        }

        return redirect()
            ->route('pesanan.pelanggan')
            ->with(
                'success',
                'Bukti transfer berhasil diunggah. Admin akan memeriksanya.'
            );
    }
}
