<?php

namespace App\Http\Controllers;

use App\Support\OwnerSalesReport;
use Illuminate\Http\Request;

class OwnerLaporanController extends Controller
{
    public function index(Request $request, OwnerSalesReport $laporan)
    {
        $filters = $laporan->filters($request);
        $pesanan = $laporan->orders($filters);
        $totalPenjualan = $laporan->totalPenjualan($pesanan);
        $jumlahTransaksi = $pesanan->count();
        $rataRata = $jumlahTransaksi > 0
            ? $totalPenjualan / $jumlahTransaksi
            : 0;

        $filterDari = $filters['dari'];
        $filterSampai = $filters['sampai'];
        $filterMetode = $filters['metode_pembayaran'] ?? '';
        $filterStatus = $filters['status_pesanan'] ?? '';

        return view('owner_laporan', compact(
            'pesanan',
            'totalPenjualan',
            'jumlahTransaksi',
            'rataRata',
            'filterDari',
            'filterSampai',
            'filterMetode',
            'filterStatus'
        ));
    }
}