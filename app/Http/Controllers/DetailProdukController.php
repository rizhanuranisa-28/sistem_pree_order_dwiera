<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class DetailProdukController extends Controller
{
    public function show($id)
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->where('status', 'aktif')
                      ->orderBy('harga', 'asc');
            }
        ])
        ->where('status', 'aktif')
        ->findOrFail($id);

        return view('detail_produk', compact('produk'));
    }
}