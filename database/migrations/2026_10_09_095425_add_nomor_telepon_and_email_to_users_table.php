<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_telepon', 20)
                ->nullable()
                ->after('nama_users');

            $table->string('email', 100)
                ->nullable()
                ->unique()
                ->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn(['nomor_telepon', 'email']);
        });
    }
};
