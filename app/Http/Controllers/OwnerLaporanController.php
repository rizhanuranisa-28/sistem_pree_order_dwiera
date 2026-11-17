<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class OwnerLaporanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'metode_pembayaran' => [
                'nullable',
                'in:cash,qris,transfer',
            ],
            'status_pesanan' => [
                'nullable',
                'in:menunggu,dikonfirmasi,dibuat,siap_diambil,selesai',
            ],
        ]);

        $filterMetode = $filters['metode_pembayaran'] ?? '';
        $filterStatus = $filters['status_pesanan'] ?? '';

        $pesanan = Pesanan::with([
            'user',
            'detailPesanans.produk',
            'pembayaran',
        ])
            ->when(
                $filterMetode !== '',
                fn ($query) => $query->whereHas(
                    'pembayaran',
                    fn ($pembayaranQuery) => $pembayaranQuery->where(
                        'metode_pembayaran',
                        $filterMetode
                    )
                )
            )
            ->when(
                $filterStatus !== '',
                fn ($query) => $query->where(
                    'status_pesanan',
                    $filterStatus
                )
            )
            ->orderByDesc('tanggal_pesan')
            ->get();

        // Total penjualan: uang yang benar-benar diterima (bayar dikurangi kembalian)
        // dari pembayaran yang sudah tercatat.
        $totalPenjualan = (float) $pesanan->sum(function ($p) {
            $b = $p->pembayaran;

            if (!$b || empty($b->tanggal_bayar)) {
                return 0;
            }

            return (float) $b->bayar - (float) $b->kembalian;
        });

        $jumlahTransaksi = $pesanan->count();

        $rataRata = $jumlahTransaksi > 0
            ? $totalPenjualan / $jumlahTransaksi
            : 0;

        return view('owner_laporan', compact(
            'pesanan',
            'totalPenjualan',
            'jumlahTransaksi',
            'rataRata',
            'filterMetode',
            'filterStatus'
        ));
    }
}