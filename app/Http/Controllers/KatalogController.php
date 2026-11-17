<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class KatalogController extends Controller
{
    public function index()
    {
        $produk = Produk::whereIn('status', ['aktif', 'tersedia'])->get();

        return view('katalog', compact('produk'));
    }
}