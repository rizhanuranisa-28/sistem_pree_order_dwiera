<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $primaryKey = 'id_customer';

    public $timestamps = true;

    protected $fillable = [
        'nama_customer',
        'nomor_telepon',
    ];

    public function pesanans()
    {
        return $this->hasMany(
            Pesanan::class,
            'id_customer',
            'id_customer'
        );
    }
}
