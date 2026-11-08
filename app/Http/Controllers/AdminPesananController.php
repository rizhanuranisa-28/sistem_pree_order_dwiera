<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\ValidasiJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with([
            'user',
            'customer',
            'detailPesanans.produk',
            'pembayaran',
            'jadwalPengambilan',
        ])
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('admin_pesanan', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:menunggu,dikonfirmasi,dibuat,siap_diambil,selesai',
        ]);

        // Ambil jadwal pesanan terlebih dahulu
        $pesananAwal = Pesanan::with('jadwalPengambilan')
            ->findOrFail($id);

        $jadwal = $pesananAwal->jadwalPengambilan;

        if (!$jadwal) {
            return redirect()
                ->route('admin.pesanan')
                ->with('error', 'Jadwal pengambilan pesanan tidak ditemukan.');
        }

        // Kunci slot jadwal agar tidak berbenturan dengan proses edit pelanggan
        ValidasiJadwal::denganKunci(
            $jadwal->tanggal_pengambilan,
            $jadwal->jam_pengambilan,
            function () use ($id, $validated) {
                DB::transaction(function () use ($id, $validated) {

                    // Kunci baris pesanan agar tidak diubah bersamaan
                    $pesanan = Pesanan::where('id_pesanan', $id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Perbarui status pesanan
                    $pesanan->update([
                        'status_pesanan' => $validated['status_pesanan'],
                    ]);
                });
            }
        );

        return redirect()
            ->route('admin.pesanan')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
