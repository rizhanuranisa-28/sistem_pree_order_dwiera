<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatBuktiPembayaran extends Model
{
    protected $table = 'riwayat_bukti_pembayaran';
    protected $primaryKey = 'id_riwayat';

    public $timestamps = false;

    protected $fillable = [
        'id_pembayaran',
        'nama_file',
        'alasan_pengembalian',
        'tanggal_upload',
        'status',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(
            Pembayaran::class,
            'id_pembayaran',
            'id_pembayaran'
        );
    }
}
