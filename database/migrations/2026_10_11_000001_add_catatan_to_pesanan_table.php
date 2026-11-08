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
        // Aman dijalankan kapan pun: kalau kolom catatan sudah ada
        // (misalnya dibuat manual lewat phpMyAdmin), migration ini dilewati.
        if (!Schema::hasColumn('pesanan', 'catatan')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('status_pesanan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pesanan', 'catatan')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }
    }
};