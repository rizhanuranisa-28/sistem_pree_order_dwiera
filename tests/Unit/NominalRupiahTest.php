<?php

namespace Tests\Unit;

use App\Support\NominalRupiah;
use PHPUnit\Framework\TestCase;

class NominalRupiahTest extends TestCase
{
    public function test_removes_thousands_separator_and_database_decimal_zeros(): void
    {
        $this->assertSame('100000', NominalRupiah::normalisasi('100.000'));
        $this->assertSame('100000', NominalRupiah::normalisasi('100000.00'));
    }

    public function test_normalizes_nested_size_prices_without_changing_size_names(): void
    {
        $this->assertSame([
            ['nama_ukuran' => '10.5 cm', 'harga' => '150000'],
        ], NominalRupiah::normalisasiHargaUkuran([
            ['nama_ukuran' => '10.5 cm', 'harga' => '150.000'],
        ]));
    }
}
