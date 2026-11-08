<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class KatalogController extends Controller
{
    public function index()
    {
        $produk = Produk::where('status', 'aktif')->get();

        return view('katalog', compact('produk'));
    }
}