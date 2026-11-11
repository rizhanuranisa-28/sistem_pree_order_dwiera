<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('produk')) {
            throw new RuntimeException('Tabel produk belum tersedia.');
        }

        if (!Schema::hasColumn('produk', 'id_produk')) {
            if (!Schema::hasColumn('produk', 'id')) {
                throw new RuntimeException('Primary key tabel produk tidak ditemukan.');
            }

            Schema::table('produk', function (Blueprint $table) {
                $table->renameColumn('id', 'id_produk');
            });
        }

        $missingColumns = [
            'nama_kue' => !Schema::hasColumn('produk', 'nama_kue'),
            'deskripsi' => !Schema::hasColumn('produk', 'deskripsi'),
            'harga' => !Schema::hasColumn('produk', 'harga'),
            'ukuran' => !Schema::hasColumn('produk', 'ukuran'),
            'gambar' => !Schema::hasColumn('produk', 'gambar'),
            'status' => !Schema::hasColumn('produk', 'status'),
        ];

        Schema::table('produk', function (Blueprint $table) use ($missingColumns) {
            if ($missingColumns['nama_kue']) {
                $table->string('nama_kue', 100)->nullable();
            }

            if ($missingColumns['deskripsi']) {
                $table->text('deskripsi')->nullable();
            }

            if ($missingColumns['harga']) {
                $table->decimal('harga', 12, 2)->default(0);
            }

            if ($missingColumns['ukuran']) {
                $table->string('ukuran', 50)->nullable();
            }

            if ($missingColumns['gambar']) {
                $table->string('gambar')->nullable();
            }

            if ($missingColumns['status']) {
                $table->string('status', 20)->default('aktif');
            }
        });
    }

    public function down(): void
    {
        throw new RuntimeException('Migrasi perbaikan tabel produk tidak dapat dibatalkan tanpa risiko kehilangan data.');
    }
};
