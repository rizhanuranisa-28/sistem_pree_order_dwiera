<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class OwnerPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with([
            'user',
            'detailPesanans.produk',
            'pembayaran',
            'jadwalPengambilan'
        ])
        ->orderBy('id_pesanan', 'desc')
        ->get();

        return view('owner_pesanan', compact('pesanan'));
    }
}