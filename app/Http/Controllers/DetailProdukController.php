<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class DetailProdukController extends Controller
{
    public function show($id)
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->whereIn('status', ['aktif', 'tersedia'])
                      ->orderBy('harga', 'asc');
            }
        ])
        ->whereIn('status', ['aktif', 'tersedia'])
        ->findOrFail($id);

        return view('detail_produk', compact('produk'));
    }
}