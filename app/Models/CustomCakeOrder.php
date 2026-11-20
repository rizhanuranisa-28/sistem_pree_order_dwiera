<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCakeOrder extends Model
{
    public const HARGA_DASAR = [
        '10' => 60000,
        '18' => 100000,
        '20' => 120000,
    ];

    public const STATUS_MENUNGGU_KONFIRMASI = 'menunggu_konfirmasi';

    public const STATUS_MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan_harga';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $table = 'custom_cake_orders';

    protected $primaryKey = 'id_custom_order';

    protected $fillable = [
        'id_users',
        'id_pesanan',
        'ukuran_cm',
        'harga_dasar',
        'tema',
        'tulisan',
        'dekorasi',
        'foto_referensi',
        'catatan_pelanggan',
        'biaya_dekorasi',
        'total_harga',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'harga_dasar' => 'decimal:2',
        'biaya_dekorasi' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public static function daftarHargaDasar(): array
    {
        $tersimpan = CustomCakeBasePrice::query()
            ->orderBy('ukuran_cm')
            ->get(['ukuran_cm', 'harga_dasar']);

        if ($tersimpan->isEmpty()) {
            return self::HARGA_DASAR;
        }

        $hargaDasar = [];
        foreach ($tersimpan as $harga) {
            $hargaDasar[(string) $harga->ukuran_cm] = (int) $harga->harga_dasar;
        }

        uksort($hargaDasar, static fn ($a, $b) => (int) $a <=> (int) $b);

        return $hargaDasar;
    }

    public static function hargaDasarUntukUkuran(string $ukuran): ?int
    {
        $harga = CustomCakeBasePrice::query()->find($ukuran);

        if ($harga) {
            return (int) $harga->harga_dasar;
        }

        if (CustomCakeBasePrice::query()->exists()) {
            return null;
        }

        return self::HARGA_DASAR[$ukuran] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
