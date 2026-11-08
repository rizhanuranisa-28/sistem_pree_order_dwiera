<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index()
    {
        try {
            DB::connection()->getPdo();

            $activeProduk = DB::table('produk')
                ->where('status', 'aktif')
                ->count();

            $totalPesanan = DB::table('pesanan')
                ->count();

            return response()->json([
                'system_name' => 'Sistem Pre-Order Kue Ulang Tahun Dwiera',
                'sprint_stage' => 'Sprint 1 - Foundation',
                'database_status' => 'CONNECTED',
                'active_produk_seeded' => $activeProduk,
                'total_pesanan' => $totalPesanan,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'system_name' => 'Sistem Pre-Order Kue Ulang Tahun Dwiera',
                'sprint_stage' => 'Sprint 1 - Foundation',
                'database_status' => 'DISCONNECTED',
                'active_produk_seeded' => 0,
                'total_pesanan' => 0,
                'timestamp' => now()->toIso8601String(),
            ], 500);
        }
    }
}
