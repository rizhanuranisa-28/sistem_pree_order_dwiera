<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    public $timestamps = false;

    protected $fillable = [
        'id_users',
        'id_customer',
        'id_jadwal',
        'tanggal_pesan',
        'tanggal_pengambilan',
        'total_harga',
        'status_pesanan',
        'catatan',
    ];

    // Relasi ke akun member atau pengguna
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_users',
            'id_users'
        );
    }

    // Relasi ke customer yang memesan langsung di toko
    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'id_customer',
            'id_customer'
        );
    }

    // Relasi ke detail produk pesanan
    public function detailPesanans()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke jadwal pengambilan
    public function jadwalPengambilan()
    {
        return $this->belongsTo(
            JadwalPengambilan::class,
            'id_jadwal',
            'id_jadwal'
        );
    }
}
