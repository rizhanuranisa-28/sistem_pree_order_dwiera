<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    public $timestamps = false;

    protected $fillable = [
        'nama_kue',
        'deskripsi',
        'harga',
        'ukuran',
        'gambar',
        'status',
    ];

    public function detailPesanans()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_produk',
            'id_produk'
        );
    }

    public function ukuranProduks()
    {
        return $this->hasMany(
            UkuranProduk::class,
            'id_produk',
            'id_produk'
        );
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        $path = 'images/produk/' . $this->gambar;

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset($path);
    }
}
