<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_base_prices', function (Blueprint $table) {
            $table->string('ukuran_cm', 10)->primary();
            $table->decimal('harga_dasar', 12, 2);
        });

        DB::table('custom_cake_base_prices')->insert([
            ['ukuran_cm' => '10', 'harga_dasar' => 60000],
            ['ukuran_cm' => '18', 'harga_dasar' => 100000],
            ['ukuran_cm' => '20', 'harga_dasar' => 120000],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_base_prices');
    }
};
