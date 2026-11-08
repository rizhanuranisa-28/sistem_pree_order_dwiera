<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\DetailPesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerGrafikController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | PERIODE GRAFIK
        |--------------------------------------------------------------------------
        */

        $tanggalMulai = Carbon::today()->subDays(6);
        $tanggalAkhir = Carbon::today();

        /*
|--------------------------------------------------------------------------
| GRAFIK 5 PRODUK TERLARIS
|--------------------------------------------------------------------------
*/

$grafikProduk = DetailPesanan::select(
        'id_produk',
        DB::raw('SUM(jumlah) as total_terjual')
    )
    ->with('produk')
    ->groupBy('id_produk')
    ->orderByDesc('total_terjual')
    ->take(5)
    ->get();

$grafikNamaProduk = $grafikProduk
    ->map(function ($item) {
        return $item->produk->nama_kue
            ?? $item->produk->nama_produk
            ?? 'Produk tidak diketahui';
    })
    ->values();

$grafikJumlahTerjual = $grafikProduk
    ->map(function ($item) {
        return (int) $item->total_terjual;
    })
    ->values();
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        $totalPendapatan = Pembayaran::sum('total_bayar');

        $totalPesanan = Pesanan::count();

        $produkTerjual = DetailPesanan::sum('jumlah');

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS
        |--------------------------------------------------------------------------
        */

        $produkTerlaris = Produk::withSum(
            'detailPesanans',
            'jumlah'
        )
            ->withSum(
                'detailPesanans',
                'subtotal'
            )
            ->get()
            ->map(function ($produk) {
                $produk->jumlah_terjual =
                    (int) ($produk->detail_pesanans_sum_jumlah ?? 0);

                $produk->pendapatan_produk =
                    (float) ($produk->detail_pesanans_sum_subtotal ?? 0);

                return $produk;
            })
            ->sortByDesc('jumlah_terjual')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PRODUK PALING SEDIKIT TERJUAL
        |--------------------------------------------------------------------------
        */

        $produkKurangLaku = $produkTerlaris
            ->sortBy('jumlah_terjual')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TOP 4 PRODUK TERLARIS
        |--------------------------------------------------------------------------
        */

        $topProduk = $produkTerlaris
            ->take(4);

        /*
        |--------------------------------------------------------------------------
        | TOP 4 PRODUK KURANG LAKU
        |--------------------------------------------------------------------------
        */

        $bottomProduk = $produkKurangLaku
            ->take(4);

        /*
        |--------------------------------------------------------------------------
        | PRODUK PALING LARIS
        |--------------------------------------------------------------------------
        */

        $produkPalingLaris = $produkTerlaris->first();

        /*
        |--------------------------------------------------------------------------
        | PRODUK PALING SEDIKIT TERJUAL
        |--------------------------------------------------------------------------
        */

        $produkPalingSedikit = $produkKurangLaku->first();

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN GRAFIK OWNER
        |--------------------------------------------------------------------------
        */

        return view('owner_grafik', compact(
            'grafikNamaProduk',
            'grafikJumlahTerjual',
            'totalPendapatan',
            'totalPesanan',
            'produkTerjual',
            'topProduk',
            'bottomProduk',
            'produkPalingLaris',
            'produkPalingSedikit'
        ));
    }
}