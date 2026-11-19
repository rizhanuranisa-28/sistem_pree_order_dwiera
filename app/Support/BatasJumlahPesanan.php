<?php

namespace App\Support;

class BatasJumlahPesanan
{
    public const MAKSIMAL_KUE = 2;

    public static function total(array $items): int
    {
        return array_sum(array_map(
            static function ($item): int {
                return is_array($item)
                    ? max(0, (int) ($item['jumlah'] ?? 1))
                    : 0;
            },
            $items
        ));
    }

    public static function pesanMaksimal(): string
    {
        return 'Maksimal pemesanan ' . self::MAKSIMAL_KUE . ' kue dalam satu transaksi.';
    }
}
