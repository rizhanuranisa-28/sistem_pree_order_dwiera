<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UkuranProduk extends Model
{
    protected $table = 'ukuran_produk';

    protected $primaryKey = 'id_ukuran';

    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'nama_ukuran',
        'harga',
        'status',
    ];

    public function produk()
    {
        return $this->belongsTo(
            Produk::class,
            'id_produk',
            'id_produk'
        );
    }
}
