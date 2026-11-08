<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        // Total penjualan
        $totalPenjualan = Pembayaran::sum('total_bayar');

        // Total seluruh pesanan
        $totalPesanan = Pesanan::count();

        // Pesanan selesai
        $pesananSelesai = Pesanan::where(
            'status_pesanan',
            'selesai'
        )->count();

        // Pesanan yang masih berjalan
        $pesananPending = Pesanan::whereIn(
            'status_pesanan',
            [
                'menunggu',
                'dikonfirmasi',
                'dibuat',
                'siap_diambil'
            ]
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Grafik 5 Kue Terlaris
        |--------------------------------------------------------------------------
        |
        | Menghitung jumlah kue yang terjual berdasarkan detail pesanan
        | dengan status pesanan selesai.
        |
        */

       // Grafik penjualan per bulan untuk 5 kue terlaris
$topProduk = DB::table('detail_pesanan')
    ->join(
        'pesanan',
        'detail_pesanan.id_pesanan',
        '=',
        'pesanan.id_pesanan'
    )
    ->join(
        'produk',
        'detail_pesanan.id_produk',
        '=',
        'produk.id_produk'
    )
    ->where('pesanan.status_pesanan', 'selesai')
    ->select(
        'produk.id_produk',
        'produk.nama_kue',
        DB::raw('SUM(detail_pesanan.jumlah) as total_terjual')
    )
    ->groupBy('produk.id_produk', 'produk.nama_kue')
    ->orderByDesc('total_terjual')
    ->limit(5)
    ->get();

$grafikNamaProduk = $topProduk
    ->pluck('nama_kue')
    ->values();

$grafikDataBulanan = collect();

foreach (range(1, 12) as $bulan) {
    $baris = [
        'bulan' => \Carbon\Carbon::create()
            ->month($bulan)
            ->translatedFormat('M'),
    ];

    foreach ($topProduk as $produk) {
        $jumlah = DB::table('detail_pesanan')
            ->join(
                'pesanan',
                'detail_pesanan.id_pesanan',
                '=',
                'pesanan.id_pesanan'
            )
            ->where('pesanan.status_pesanan', 'selesai')
            ->where('detail_pesanan.id_produk', $produk->id_produk)
            ->whereMonth('pesanan.tanggal_pesan', $bulan)
            ->whereYear('pesanan.tanggal_pesan', now()->year)
            ->sum('detail_pesanan.jumlah');

        $baris[$produk->nama_kue] = (int) $jumlah;
    }

    $grafikDataBulanan->push($baris);
}

        // Pesanan terbaru
        $pesananTerbaru = Pesanan::with([
                'user',
                'detailPesanans.produk'
            ])
            ->orderBy('id_pesanan', 'desc')
            ->limit(5)
            ->get();

       return view('owner', compact(
    'totalPenjualan',
    'totalPesanan',
    'pesananSelesai',
    'pesananPending',
    'pesananTerbaru',
    'grafikNamaProduk',
    'grafikDataBulanan'
));
    }
}
