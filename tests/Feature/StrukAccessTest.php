<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StrukAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        $this->createOrdersAndPayments();

        $admin = (new User())->forceFill([
            'id_users' => 1,
            'nama_users' => 'Admin',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_cash_receipt_is_available_before_order_is_complete(): void
    {
        $this->get(route('struk.admin', 1))
            ->assertOk()
            ->assertSee('#DW0001')
            ->assertSee('Dibuat');
    }

    public function test_transfer_and_qris_receipts_require_completed_orders(): void
    {
        foreach ([2, 3] as $orderId) {
            $this->from(route('admin.pembayaran'))
                ->get(route('struk.admin', $orderId))
                ->assertRedirect(route('admin.pembayaran'))
                ->assertSessionHas(
                    'error',
                    'Struk pembayaran transfer atau QRIS hanya dapat dilihat setelah pesanan selesai.'
                );
        }
    }

    public function test_completed_transfer_receipt_remains_available(): void
    {
        $this->get(route('struk.admin', 4))
            ->assertOk()
            ->assertSee('#DW0004');
    }

    public function test_payment_list_only_shows_receipts_when_allowed_by_method_and_status(): void
    {
        $response = $this->get(route('admin.pembayaran'));

        $response->assertOk()
            ->assertSee(route('struk.admin', 1), false)
            ->assertDontSee(route('struk.admin', 2), false)
            ->assertDontSee(route('struk.admin', 3), false)
            ->assertSee(route('struk.admin', 4), false)
            ->assertSee('Struk tersedia setelah pesanan selesai.');
    }

    private function createSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_users');
            $table->string('nama_users');
            $table->string('nomor_telepon')->nullable();
            $table->string('role');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id_customer');
            $table->string('nama_customer')->nullable();
            $table->string('nomor_telepon')->nullable();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->unsignedInteger('id_users')->nullable();
            $table->unsignedInteger('id_customer')->nullable();
            $table->unsignedInteger('id_jadwal')->nullable();
            $table->dateTime('tanggal_pesan')->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status_pesanan')->nullable();
        });

        Schema::create('custom_cake_orders', function (Blueprint $table) {
            $table->increments('id_custom_order');
            $table->integer('id_users');
            $table->integer('id_pesanan')->nullable();
            $table->string('ukuran_cm', 10);
            $table->decimal('harga_dasar', 12, 2);
            $table->string('tema', 100);
            $table->string('tulisan', 100);
            $table->text('dekorasi');
            $table->string('foto_referensi')->nullable();
            $table->text('catatan_pelanggan')->nullable();
            $table->decimal('biaya_dekorasi', 12, 2)->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status', 40);
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
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
            $table->integer('jumlah')->nullable();
            $table->decimal('harga_satuan', 12, 2)->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_kue')->nullable();
        });

        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->increments('id_jadwal');
            $table->date('tanggal_pengambilan')->nullable();
            $table->string('jam_pengambilan')->nullable();
        });

        Schema::create('riwayat_bukti_pembayaran', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->unsignedInteger('id_pembayaran');
        });
    }

    private function createOrdersAndPayments(): void
    {
        DB::table('users')->insert([
            'id_users' => 1,
            'nama_users' => 'Admin',
            'nomor_telepon' => '08123456789',
            'role' => 'admin',
        ]);

        DB::table('pesanan')->insert([
            ['id_pesanan' => 1, 'id_users' => 1, 'total_harga' => 10000, 'status_pesanan' => 'dibuat'],
            ['id_pesanan' => 2, 'id_users' => 1, 'total_harga' => 10000, 'status_pesanan' => 'dibuat'],
            ['id_pesanan' => 3, 'id_users' => 1, 'total_harga' => 10000, 'status_pesanan' => 'dibuat'],
            ['id_pesanan' => 4, 'id_users' => 1, 'total_harga' => 10000, 'status_pesanan' => 'selesai'],
        ]);

        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pesanan' => 1,
                'tanggal_bayar' => '2026-11-19 10:00:00',
                'metode_pembayaran' => 'cash',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 2,
                'id_pesanan' => 2,
                'tanggal_bayar' => '2026-11-19 10:00:00',
                'metode_pembayaran' => 'transfer',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 3,
                'id_pesanan' => 3,
                'tanggal_bayar' => '2026-11-19 10:00:00',
                'metode_pembayaran' => 'qris',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 4,
                'id_pesanan' => 4,
                'tanggal_bayar' => '2026-11-19 10:00:00',
                'metode_pembayaran' => 'transfer',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
        ]);
    }
}
