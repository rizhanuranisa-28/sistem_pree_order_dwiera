<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'tanggal_bayar',
        'metode_pembayaran',
        'jumlah_bayar',
        'total_bayar',
        'bayar',
        'kembalian',
        'bukti_pembayaran',
        'status_bukti',
        'alasan_pengembalian',
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function riwayatBukti()
    {
        return $this->hasMany(
            RiwayatBuktiPembayaran::class,
            'id_pembayaran',
            'id_pembayaran'
        );
    }
}
