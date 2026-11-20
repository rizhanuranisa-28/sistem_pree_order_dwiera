<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCakeBasePrice extends Model
{
    protected $table = 'custom_cake_base_prices';

    protected $primaryKey = 'ukuran_cm';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ukuran_cm',
        'harga_dasar',
    ];

    protected $casts = [
        'harga_dasar' => 'integer',
    ];
}
