<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminPaymentFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        $this->createPayments();

        $admin = (new User())->forceFill([
            'id_users' => 1,
            'nama_users' => 'Admin',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_unfiltered_history_shows_every_payment_and_total(): void
    {
        $response = $this->get(route('admin.pembayaran'));

        $response->assertOk()
            ->assertSee('#PAY0001')
            ->assertSee('#PAY0002')
            ->assertSee('#PAY0003')
            ->assertSee('Permintaan Custom')
            ->assertSee(route('admin.custom-orders.index'))
            ->assertSee('🏠')
            ->assertSee('🛍️')
            ->assertSee('🚪')
            ->assertSee('Rp 60.000');
    }

    public function test_payment_method_filter_keeps_the_selected_value_and_updates_total(): void
    {
        $response = $this->get(route('admin.pembayaran', [
            'metode_pembayaran' => 'qris',
        ]));

        $response->assertOk()
            ->assertSee('#PAY0003')
            ->assertDontSee('#PAY0001')
            ->assertDontSee('#PAY0002')
            ->assertSee('Rp 30.000')
            ->assertSee('value="qris" selected', false);
    }

    public function test_order_status_filter_updates_the_payment_total(): void
    {
        $response = $this->get(route('admin.pembayaran', [
            'status_pesanan' => 'menunggu',
        ]));

        $response->assertOk()
            ->assertSee('#PAY0002')
            ->assertDontSee('#PAY0001')
            ->assertDontSee('#PAY0003')
            ->assertSee('Rp 20.000')
            ->assertSee('value="menunggu" selected', false);
    }

    public function test_payment_and_order_status_filters_can_be_combined(): void
    {
        $response = $this->get(route('admin.pembayaran', [
            'metode_pembayaran' => 'cash',
            'status_pesanan' => 'selesai',
        ]));

        $response->assertOk()
            ->assertSee('#PAY0001')
            ->assertDontSee('#PAY0002')
            ->assertDontSee('#PAY0003')
            ->assertSee('Rp 10.000')
            ->assertSee('value="cash" selected', false)
            ->assertSee('value="selesai" selected', false);
    }

    public function test_empty_filter_result_shows_zero_total(): void
    {
        $response = $this->get(route('admin.pembayaran', [
            'metode_pembayaran' => 'cash',
            'status_pesanan' => 'menunggu',
        ]));

        $response->assertOk()
            ->assertSee('Data pembayaran tidak ditemukan untuk filter yang dipilih.')
            ->assertSee('Rp 0');
    }

    public function test_admin_navigation_and_payment_rows_mark_unreviewed_items(): void
    {
        DB::table('pembayaran')
            ->where('id_pembayaran', 2)
            ->update([
                'bukti_pembayaran' => 'bukti/transfer-menunggu.png',
                'status_bukti' => 'menunggu',
            ]);

        DB::table('custom_cake_orders')->insert([
            'id_users' => 1,
            'status' => 'menunggu_konfirmasi',
        ]);

        $this->get(route('admin.pembayaran'))
            ->assertOk()
            ->assertSee('aria-label="1 pesanan belum diperiksa"', false)
            ->assertSee('aria-label="1 permintaan custom belum diperiksa"', false)
            ->assertSee('aria-label="1 bukti pembayaran belum diperiksa"', false)
            ->assertSee('class="unread-dot"', false);
    }

    private function createSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_users');
            $table->string('nama_users');
            $table->string('role');
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->unsignedInteger('id_users')->nullable();
            $table->string('status_pesanan')->nullable();
        });

        Schema::create('custom_cake_orders', function (Blueprint $table) {
            $table->increments('id_custom_order');
            $table->unsignedInteger('id_users')->nullable();
            $table->unsignedInteger('id_pesanan')->nullable();
            $table->string('ukuran_cm')->nullable();
            $table->string('tema')->nullable();
            $table->string('tulisan')->nullable();
            $table->text('dekorasi')->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status')->nullable();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_pesanan')->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->decimal('total_bayar', 12, 2)->nullable();
            $table->decimal('bayar', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_bukti')->nullable();
            $table->text('alasan_pengembalian')->nullable();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_pesanan');
            $table->unsignedInteger('id_produk')->nullable();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_kue')->nullable();
        });

        Schema::create('riwayat_bukti_pembayaran', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->unsignedInteger('id_pembayaran');
        });
    }

    private function createPayments(): void
    {
        DB::table('users')->insert([
            'id_users' => 1,
            'nama_users' => 'Admin',
            'role' => 'admin',
        ]);

        DB::table('pesanan')->insert([
            ['id_pesanan' => 101, 'id_users' => 1, 'status_pesanan' => 'selesai'],
            ['id_pesanan' => 102, 'id_users' => 1, 'status_pesanan' => 'menunggu'],
            ['id_pesanan' => 103, 'id_users' => 1, 'status_pesanan' => 'selesai'],
        ]);

        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pesanan' => 101,
                'metode_pembayaran' => 'cash',
                'total_bayar' => 10000,
            ],
            [
                'id_pembayaran' => 2,
                'id_pesanan' => 102,
                'metode_pembayaran' => 'transfer',
                'total_bayar' => 20000,
            ],
            [
                'id_pembayaran' => 3,
                'id_pesanan' => 103,
                'metode_pembayaran' => 'qris',
                'total_bayar' => 30000,
            ],
        ]);
    }
}
