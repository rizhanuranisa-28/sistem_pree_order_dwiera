<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\RiwayatBuktiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminPembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index()
    {
        abort_unless(
            Auth::user()->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses admin.'
        );

        $pembayaran = Pembayaran::with([
            'pesanan.user',
            'pesanan.detailPesanans.produk',
            'riwayatBukti',
        ])
            ->orderByDesc('id_pembayaran')
            ->get();

        return view('admin_pembayaran', compact('pembayaran'));
    }

    /**
     * Upload bukti transfer oleh admin untuk pesanan offline.
     */
    public function uploadBukti(Request $request, $id)
    {
        abort_unless(
            Auth::user()->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses admin.'
        );

        $validated = $request->validate([
            'bukti_pembayaran' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ], [
            'bukti_pembayaran.required' => 'Pilih gambar bukti transfer terlebih dahulu.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $pembayaran = Pembayaran::with('pesanan')
            ->findOrFail($id);

        if (
            strtolower((string) $pembayaran->metode_pembayaran)
            !== 'transfer'
        ) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Upload bukti hanya berlaku untuk pembayaran transfer.');
        }

        if ($pembayaran->tanggal_bayar !== null) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Pembayaran sudah dikonfirmasi dan tidak dapat diganti.');
        }

        /*
         * Pesanan offline dibuat dengan id_users NULL.
         * Upload melalui fungsi ini hanya untuk pesanan offline.
         */
        if (
            !$pembayaran->pesanan ||
            $pembayaran->pesanan->id_users !== null
        ) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Upload melalui fitur ini hanya berlaku untuk pesanan offline.');
        }

        $file = $validated['bukti_pembayaran'];

        /*
         * Simpan gambar di storage/app/public/bukti.
         */
        $namaFile = $file->store('bukti', 'public');

        try {
            DB::transaction(function () use (
                $pembayaran,
                $namaFile
            ) {
                $data = Pembayaran::where(
                    'id_pembayaran',
                    $pembayaran->id_pembayaran
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($data->tanggal_bayar !== null) {
                    throw ValidationException::withMessages([
                        'pembayaran' => 'Pembayaran sudah dikonfirmasi dan tidak dapat diganti.',
                    ]);
                }

                if (
                    strtolower((string) $data->metode_pembayaran)
                    !== 'transfer'
                ) {
                    throw ValidationException::withMessages([
                        'pembayaran' => 'Upload bukti hanya berlaku untuk pembayaran transfer.',
                    ]);
                }

                if (
                    !$data->pesanan()->exists() ||
                    $data->pesanan()->value('id_users') !== null
                ) {
                    throw ValidationException::withMessages([
                        'pembayaran' => 'Upload melalui fitur ini hanya berlaku untuk pesanan offline.',
                    ]);
                }

                $namaFileLama = $data->bukti_pembayaran;

                /*
                 * Simpan bukti terbaru sebagai bukti yang menunggu
                 * verifikasi admin.
                 */
                $data->update([
                    'bukti_pembayaran' => $namaFile,
                    'status_bukti' => 'menunggu',
                    'alasan_pengembalian' => null,
                ]);

                /*
                 * Catat bukti terbaru dalam riwayat.
                 * Struktur mengikuti kolom yang digunakan
                 * controller pembayaran saat ini.
                 */
                RiwayatBuktiPembayaran::create([
                    'id_pembayaran' => $data->id_pembayaran,
                    'nama_file' => $namaFile,
                    'status' => 'menunggu',
                    'alasan_pengembalian' => null,
                ]);

                /*
                 * Hapus bukti lama hanya jika tersimpan di disk public
                 * dan tidak lagi digunakan oleh riwayat.
                 *
                 * File lama yang masih tercatat di riwayat tidak dihapus.
                 */
                if (
                    $namaFileLama &&
                    $namaFileLama !== $namaFile &&
                    !RiwayatBuktiPembayaran::where(
                        'nama_file',
                        $namaFileLama
                    )->exists() &&
                    Storage::disk('public')->exists($namaFileLama)
                ) {
                    Storage::disk('public')->delete($namaFileLama);
                }
            });
        } catch (\Throwable $e) {
            /*
             * Bersihkan file baru jika penyimpanan database gagal.
             */
            Storage::disk('public')->delete($namaFile);

            throw $e;
        }

        return redirect()
            ->route('admin.pembayaran')
            ->with(
                'success',
                'Bukti transfer berhasil diunggah. Pastikan dana masuk sebelum mengonfirmasi pembayaran.'
            );
    }

    /**
     * Memproses pembayaran cash atau transfer.
     */
    public function proses(Request $request, $id)
    {
        abort_unless(
            Auth::user()->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses admin.'
        );

        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->tanggal_bayar !== null) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Pembayaran ini sudah dikonfirmasi.');
        }

        $metode = strtolower($pembayaran->metode_pembayaran ?? '');

        if (!in_array($metode, ['cash', 'transfer'], true)) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Metode pembayaran tidak valid.');
        }

        $total = (float) ($pembayaran->total_bayar ?? 0);

        if ($total <= 0) {
            return redirect()
                ->route('admin.pembayaran')
                ->with('error', 'Total tagihan tidak valid.');
        }

        if ($metode === 'transfer') {
            if (empty($pembayaran->bukti_pembayaran)) {
                return redirect()
                    ->route('admin.pembayaran')
                    ->with(
                        'error',
                        'Belum ada bukti transfer yang diunggah.'
                    );
            }

            if (($pembayaran->status_bukti ?? 'menunggu') !== 'menunggu') {
                return redirect()
                    ->route('admin.pembayaran')
                    ->with(
                        'error',
                        'Bukti transfer harus berstatus menunggu sebelum dikonfirmasi.'
                    );
            }

            $diterima = $total;
            $bayar = $total;
            $kembalian = 0;
        } else {
            $validated = $request->validate([
                'jumlah_bayar' => [
                    'required',
                    'numeric',
                    'min:' . $total,
                    'max:100000000000',
                ],
            ]);

            $diterima = (float) $validated['jumlah_bayar'];
            $bayar = $total;
            $kembalian = $diterima - $total;
        }

        DB::transaction(function () use (
            $id,
            $metode,
            $diterima,
            $bayar,
            $kembalian
        ) {
            $data = Pembayaran::where('id_pembayaran', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->tanggal_bayar !== null) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Pembayaran sudah dikonfirmasi sebelumnya.',
                ]);
            }

            if (
                $metode === 'transfer' &&
                (
                    empty($data->bukti_pembayaran) ||
                    ($data->status_bukti ?? 'menunggu') !== 'menunggu'
                )
            ) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Bukti transfer belum tersedia atau belum siap dikonfirmasi.',
                ]);
            }

            $data->update([
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $diterima,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'status_bukti' => $metode === 'transfer'
                    ? 'diterima'
                    : $data->status_bukti,
                'alasan_pengembalian' => null,
            ]);

            if ($metode === 'transfer') {
                RiwayatBuktiPembayaran::where(
                    'id_pembayaran',
                    $data->id_pembayaran
                )
                    ->where('nama_file', $data->bukti_pembayaran)
                    ->update([
                        'status' => 'diterima',
                        'alasan_pengembalian' => null,
                    ]);
            }
        });

        return redirect()
            ->route('admin.pembayaran')
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Mengembalikan bukti transfer dengan alasan.
     */
    public function kembalikan(Request $request, $id)
    {
        abort_unless(
            Auth::user()->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses admin.'
        );

        $validated = $request->validate([
            'alasan_pengembalian' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ], [
            'alasan_pengembalian.required' => 'Alasan pengembalian wajib diisi.',
            'alasan_pengembalian.min' => 'Alasan minimal 5 karakter.',
            'alasan_pengembalian.max' => 'Alasan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($id, $validated) {
            $data = Pembayaran::where('id_pembayaran', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->tanggal_bayar !== null) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Pembayaran yang sudah dikonfirmasi tidak dapat dikembalikan.',
                ]);
            }

            if (
                strtolower((string) $data->metode_pembayaran)
                !== 'transfer'
            ) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Pengembalian bukti hanya berlaku untuk pembayaran transfer.',
                ]);
            }

            if (empty($data->bukti_pembayaran)) {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Belum ada bukti transfer untuk dikembalikan.',
                ]);
            }

            if (($data->status_bukti ?? 'menunggu') !== 'menunggu') {
                throw ValidationException::withMessages([
                    'pembayaran' => 'Bukti ini sudah diproses. Tunggu bukti baru diunggah.',
                ]);
            }

            $alasan = $validated['alasan_pengembalian'];

            $data->update([
                'status_bukti' => 'dikembalikan',
                'alasan_pengembalian' => $alasan,
            ]);

            RiwayatBuktiPembayaran::where(
                'id_pembayaran',
                $data->id_pembayaran
            )
                ->where('nama_file', $data->bukti_pembayaran)
                ->update([
                    'status' => 'dikembalikan',
                    'alasan_pengembalian' => $alasan,
                ]);
        });

        return redirect()
            ->route('admin.pembayaran')
            ->with(
                'success',
                'Bukti transfer berhasil dikembalikan.'
            );
    }
}
