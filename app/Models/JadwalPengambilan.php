<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengambilan extends Model
{
    protected $table = 'jadwal_pengambilan';
    protected $primaryKey = 'id_jadwal';

    public $timestamps = false;

    protected $fillable = [
        'tanggal_pengambilan',
        'jam_pengambilan',
        'status_pengambilan',
    ];

    public function pesanan()
    {
        return $this->hasOne(
            Pesanan::class,
            'id_jadwal',
            'id_jadwal'
        );
    }
}
