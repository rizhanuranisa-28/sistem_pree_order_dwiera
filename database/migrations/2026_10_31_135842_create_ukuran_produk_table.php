<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ukuran_produk', function (Blueprint $table) {
            $table->increments('id_ukuran');

            $table->integer('id_produk');

            $table->string('nama_ukuran', 50);

            $table->decimal('harga', 12, 2);

            $table->string('status', 20)->default('aktif');

            $table->unique(
                ['id_produk', 'nama_ukuran'],
                'ukuran_produk_unique'
            );

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukuran_produk');
    }
};