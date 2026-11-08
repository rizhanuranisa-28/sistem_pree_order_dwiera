<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class OwnerLaporanController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with([
            'user',
            'detailPesanans.produk',
            'pembayaran',
        ])
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
            'rataRata'
        ));
    }
}