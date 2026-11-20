<?php

namespace App\Support;

class NominalRupiah
{
    public static function normalisasi(mixed $nilai): mixed
    {
        if (!is_string($nilai)) {
            return $nilai;
        }

        $nilai = trim($nilai);
        $nilai = preg_replace('/\.00$/', '', $nilai);

        return str_replace('.', '', $nilai);
    }

    public static function normalisasiHargaUkuran(mixed $ukuranProduk): mixed
    {
        if (!is_array($ukuranProduk)) {
            return $ukuranProduk;
        }

        foreach ($ukuranProduk as $index => $ukuran) {
            if (is_array($ukuran) && array_key_exists('harga', $ukuran)) {
                $ukuranProduk[$index]['harga'] = self::normalisasi($ukuran['harga']);
            }
        }

        return $ukuranProduk;
    }
}
