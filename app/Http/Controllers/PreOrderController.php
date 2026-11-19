<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Support\ValidasiJadwal;

class PreOrderController extends Controller
{
    public function create($id)
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->whereIn('status', ['aktif', 'tersedia'])
                      ->orderBy('harga', 'asc');
            }
        ])->findOrFail($id);

        $sekarang = now(config('app.timezone', 'Asia/Jakarta'));
        $batasMinimalJadwal = ValidasiJadwal::batasMinimal($sekarang);
        $waktuPalingCepat = ValidasiJadwal::waktuPalingCepat($sekarang);
        $pesanJadwalMinimum = ValidasiJadwal::pesanJadwalMinimum($sekarang);

        return view('pre_order', compact(
            'produk',
            'batasMinimalJadwal',
            'waktuPalingCepat',
            'pesanJadwalMinimum'
        ));
    }
}
