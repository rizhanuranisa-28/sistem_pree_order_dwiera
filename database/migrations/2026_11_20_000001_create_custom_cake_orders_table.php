<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_orders', function (Blueprint $table) {
            $table->increments('id_custom_order');
            $table->integer('id_users');
            $table->integer('id_pesanan')->nullable()->unique();
            $table->string('ukuran_cm', 10);
            $table->decimal('harga_dasar', 12, 2);
            $table->string('tema', 100);
            $table->string('tulisan', 100);
            $table->text('dekorasi');
            $table->string('foto_referensi')->nullable();
            $table->text('catatan_pelanggan')->nullable();
            $table->decimal('biaya_dekorasi', 12, 2)->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status', 40)->default('menunggu_konfirmasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_orders');
    }
};
