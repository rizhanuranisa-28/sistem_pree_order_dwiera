<?php

namespace Tests\Feature;

use App\Support\ValidasiJadwal;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ValidasiJadwalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.timezone' => 'Asia/Jakarta']);

        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->increments('id_jadwal');
            $table->date('tanggal_pengambilan');
            $table->time('jam_pengambilan');
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->unsignedInteger('id_jadwal');
            $table->unsignedInteger('id_users')->nullable();
            $table->unsignedInteger('id_customer')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id_users')->primary();
            $table->string('nomor_telepon')->nullable();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedInteger('id_customer')->primary();
            $table->string('nomor_telepon')->nullable();
        });

        DB::connection()->getPdo()->sqliteCreateFunction(
            'HOUR',
            static fn ($value) => (int) substr((string) $value, 0, 2)
        );

        Carbon::setTestNow(
            Carbon::parse('2026-11-19 11:00:00', 'Asia/Jakarta')
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_rejects_pickup_less_than_three_hours_ahead_with_recommendation(): void
    {
        $pesan = ValidasiJadwal::cek('2026-11-19', '13:59');

        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertStringContainsString('minimal 3 jam', $pesan);
        $this->assertStringContainsString('pukul 14.00', $pesan);
        $this->assertStringContainsString('pukul 14.00 atau setelahnya', $pesan);
    }

    public function test_accepts_pickup_exactly_three_hours_ahead(): void
    {
        $this->assertNull(ValidasiJadwal::cek('2026-11-19', '14:00'));
    }

    public function test_accepts_pickup_more_than_three_hours_ahead(): void
    {
        $this->assertNull(ValidasiJadwal::cek('2026-11-19', '15:00'));
    }

    public function test_recommends_next_selectable_minute_when_order_time_has_seconds(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-11-19 11:00:30', 'Asia/Jakarta')
        );

        $this->assertSame(
            '14:01',
            ValidasiJadwal::waktuPalingCepat()->format('H:i')
        );
        $this->assertStringContainsString(
            'pukul 14.01',
            ValidasiJadwal::cek('2026-11-19', '14:00')
        );
    }

    public function test_schedule_form_uses_server_calculated_minimum_and_recommendation(): void
    {
        $sekarang = Carbon::now('Asia/Jakarta');
        $batasMinimalJadwal = ValidasiJadwal::batasMinimal($sekarang);
        $waktuPalingCepat = ValidasiJadwal::waktuPalingCepat($sekarang);
        $pesanJadwalMinimum = ValidasiJadwal::pesanJadwalMinimum($sekarang);

        $html = view('partials.validasi_jadwal_minimum', compact(
            'batasMinimalJadwal',
            'waktuPalingCepat',
            'pesanJadwalMinimum'
        ))->render();

        $this->assertStringContainsString('2026-11-19 14:00', $html);
        $this->assertStringContainsString('pukul 14.00', $html);
        $this->assertStringContainsString('jam.setCustomValidity', $html);
    }

    public function test_rejects_fully_booked_pickup_hour_with_clear_message(): void
    {
        foreach (['14:00:00', '14:30:00'] as $jam) {
            $idJadwal = DB::table('jadwal_pengambilan')->insertGetId([
                'tanggal_pengambilan' => '2026-11-19',
                'jam_pengambilan' => $jam,
            ]);

            $idUser = DB::table('users')->insertGetId([
                'nomor_telepon' => '08123456789' . $idJadwal,
            ]);

            DB::table('pesanan')->insert([
                'id_jadwal' => $idJadwal,
                'id_users' => $idUser,
                'id_customer' => null,
            ]);
        }

        $this->assertSame(
            'Pesanan Penuh. Jadwal ini sudah mencapai batas maksimal. '
                . 'Silakan pilih jam pengambilan minimal 3 jam setelah pemesanan dilakukan.',
            ValidasiJadwal::cek('2026-11-19', '14:00')
        );
    }
}
