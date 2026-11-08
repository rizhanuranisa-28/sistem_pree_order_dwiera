<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class PreOrderController extends Controller
{
    public function create($id)
    {
        $produk = Produk::with([
            'ukuranProduks' => function ($query) {
                $query->where('status', 'aktif')
                      ->orderBy('harga', 'asc');
            }
        ])->findOrFail($id);

        return view('pre_order', compact('produk'));
    }
}
