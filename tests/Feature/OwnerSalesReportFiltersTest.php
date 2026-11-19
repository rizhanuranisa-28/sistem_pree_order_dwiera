<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OwnerSalesReportFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-30 12:00:00');
        $this->createSchema();
        $this->createOrdersAndPayments();

        $owner = (new User())->forceFill([
            'id_users' => 1,
            'nama_users' => 'Owner',
            'role' => 'owner',
        ]);

        $this->actingAs($owner);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_unfiltered_report_shows_all_orders_and_sales_total(): void
    {
        $response = $this->get(route('owner.laporan'));

        $response->assertOk()
            ->assertSee('#101')
            ->assertSee('#102')
            ->assertSee('#103')
            ->assertSee('Rp 60.000');
    }

    public function test_payment_method_filter_updates_report_rows_and_total(): void
    {
        $response = $this->get(route('owner.laporan', [
            'metode_pembayaran' => 'qris',
        ]));

        $response->assertOk()
            ->assertSee('#103')
            ->assertDontSee('#101')
            ->assertDontSee('#102')
            ->assertSee('Rp 30.000')
            ->assertSee('value="qris" selected', false);
    }

    public function test_transaction_date_filter_uses_payment_date(): void
    {
        $response = $this->get(route('owner.laporan', [
            'dari' => '2026-11-01',
            'sampai' => '2026-11-01',
        ]));

        $response->assertOk()
            ->assertSee('#101')
            ->assertDontSee('#102')
            ->assertDontSee('#103')
            ->assertSee('01/11/2026 11:00')
            ->assertDontSee('31/10/2026 10:00')
            ->assertSee('formaction="' . route('owner.laporan.pdf') . '"', false);
    }

    public function test_order_status_filter_updates_report_rows_and_total(): void
    {
        $response = $this->get(route('owner.laporan', [
            'status_pesanan' => 'menunggu',
        ]));

        $response->assertOk()
            ->assertSee('#102')
            ->assertDontSee('#101')
            ->assertDontSee('#103')
            ->assertSee('Rp 20.000')
            ->assertSee('value="menunggu" selected', false);
    }

    public function test_payment_method_and_order_status_filters_can_be_combined(): void
    {
        $response = $this->get(route('owner.laporan', [
            'metode_pembayaran' => 'cash',
            'status_pesanan' => 'selesai',
        ]));

        $response->assertOk()
            ->assertSee('#101')
            ->assertDontSee('#102')
            ->assertDontSee('#103')
            ->assertSee('Rp 10.000')
            ->assertSee('value="cash" selected', false)
            ->assertSee('value="selesai" selected', false);
    }

    public function test_empty_filter_result_shows_zero_sales_total(): void
    {
        $response = $this->get(route('owner.laporan', [
            'metode_pembayaran' => 'cash',
            'status_pesanan' => 'menunggu',
        ]));

        $response->assertOk()
            ->assertSee('Belum ada data penjualan.')
            ->assertSee('Rp 0');
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
            $table->dateTime('tanggal_pesan')->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status_pesanan')->nullable();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_pesanan')->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->decimal('total_bayar', 12, 2)->nullable();
            $table->decimal('bayar', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->nullable();
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
    }

    private function createOrdersAndPayments(): void
    {
        DB::table('users')->insert([
            'id_users' => 1,
            'nama_users' => 'Owner',
            'role' => 'owner',
        ]);

        DB::table('pesanan')->insert([
            [
                'id_pesanan' => 101,
                'id_users' => 1,
                'tanggal_pesan' => '2026-10-31 10:00:00',
                'total_harga' => 10000,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 102,
                'id_users' => 1,
                'tanggal_pesan' => '2026-11-02 10:00:00',
                'total_harga' => 20000,
                'status_pesanan' => 'menunggu',
            ],
            [
                'id_pesanan' => 103,
                'id_users' => 1,
                'tanggal_pesan' => '2026-11-03 10:00:00',
                'total_harga' => 30000,
                'status_pesanan' => 'selesai',
            ],
        ]);

        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pesanan' => 101,
                'tanggal_bayar' => '2026-11-01 11:00:00',
                'metode_pembayaran' => 'cash',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 2,
                'id_pesanan' => 102,
                'tanggal_bayar' => '2026-11-02 11:00:00',
                'metode_pembayaran' => 'transfer',
                'total_bayar' => 20000,
                'bayar' => 20000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 3,
                'id_pesanan' => 103,
                'tanggal_bayar' => '2026-11-03 11:00:00',
                'metode_pembayaran' => 'qris',
                'total_bayar' => 30000,
                'bayar' => 30000,
                'kembalian' => 0,
            ],
        ]);
    }
}
