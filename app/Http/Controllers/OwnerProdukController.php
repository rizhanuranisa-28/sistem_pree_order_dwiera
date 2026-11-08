<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class OwnerProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->where('status', 'aktif');
            }
        ])
        ->orderBy('id_produk', 'desc')
        ->get();

        return view('owner_produk', compact('produk'));
    }
}