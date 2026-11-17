<?php

namespace Tests\Feature;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class OwnerSalesReportPdfFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        $this->createOrdersAndPayments();

        $owner = (new User())->forceFill([
            'id_users' => 1,
            'nama_users' => 'Owner',
            'role' => 'owner',
        ]);

        $this->actingAs($owner);
    }

    public function test_pdf_filters_by_date_range_and_payment_method(): void
    {
        $pdf = Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('setPaper')
            ->once()
            ->with('a4', 'portrait')
            ->andReturnSelf();
        $pdf->shouldReceive('download')
            ->once()
            ->with('laporan-penjualan-20261101-20261130-qris.pdf')
            ->andReturn(response('PDF', 200, ['Content-Type' => 'application/pdf']));

        Pdf::shouldReceive('loadView')
            ->once()
            ->with(
                'pdf.laporan_penjualan',
                Mockery::on(function (array $data): bool {
                    $this->assertSame(
                        [102, 103],
                        $data['pesanan']->pluck('id_pesanan')->all()
                    );
                    $this->assertSame('qris', $data['metodePembayaran']);
                    $this->assertSame(
                        ['QRIS'],
                        array_column($data['perMetode'], 'nama')
                    );
                    $this->assertSame(2, $data['totalPesanan']);

                    return true;
                })
            )
            ->andReturn($pdf);

        $response = $this->get(route('owner.laporan.pdf', [
            'dari' => '2026-11-01',
            'sampai' => '2026-11-30',
            'metode_pembayaran' => 'qris',
        ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_can_combine_payment_method_and_order_status_filters(): void
    {
        $pdf = Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('setPaper')
            ->once()
            ->with('a4', 'portrait')
            ->andReturnSelf();
        $pdf->shouldReceive('download')
            ->once()
            ->with('laporan-penjualan-20261101-20261130-qris.pdf')
            ->andReturn(response('PDF', 200, ['Content-Type' => 'application/pdf']));

        Pdf::shouldReceive('loadView')
            ->once()
            ->with(
                'pdf.laporan_penjualan',
                Mockery::on(function (array $data): bool {
                    $this->assertSame(
                        [103],
                        $data['pesanan']->pluck('id_pesanan')->all()
                    );
                    $this->assertSame('selesai', $data['statusPesanan']);

                    return true;
                })
            )
            ->andReturn($pdf);

        $response = $this->get(route('owner.laporan.pdf', [
            'dari' => '2026-11-01',
            'sampai' => '2026-11-30',
            'metode_pembayaran' => 'qris',
            'status_pesanan' => 'selesai',
        ]));

        $response->assertOk();
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
            $table->string('nama_customer');
            $table->string('nomor_telepon')->nullable();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->unsignedInteger('id_users')->nullable();
            $table->unsignedInteger('id_customer')->nullable();
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
            $table->string('status')->nullable();
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
                'tanggal_pesan' => '2026-11-02 10:00:00',
                'total_harga' => 10000,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 102,
                'id_users' => 1,
                'tanggal_pesan' => '2026-11-15 10:00:00',
                'total_harga' => 20000,
                'status_pesanan' => 'menunggu',
            ],
            [
                'id_pesanan' => 103,
                'id_users' => 1,
                'tanggal_pesan' => '2026-11-20 10:00:00',
                'total_harga' => 30000,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 104,
                'id_users' => 1,
                'tanggal_pesan' => '2026-12-01 10:00:00',
                'total_harga' => 40000,
                'status_pesanan' => 'selesai',
            ],
        ]);

        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pesanan' => 101,
                'tanggal_bayar' => '2026-11-02 11:00:00',
                'metode_pembayaran' => 'cash',
                'total_bayar' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 2,
                'id_pesanan' => 102,
                'tanggal_bayar' => '2026-11-15 11:00:00',
                'metode_pembayaran' => 'qris',
                'total_bayar' => 20000,
                'bayar' => 20000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 3,
                'id_pesanan' => 103,
                'tanggal_bayar' => '2026-11-20 11:00:00',
                'metode_pembayaran' => 'qris',
                'total_bayar' => 30000,
                'bayar' => 30000,
                'kembalian' => 0,
            ],
            [
                'id_pembayaran' => 4,
                'id_pesanan' => 104,
                'tanggal_bayar' => '2026-12-01 11:00:00',
                'metode_pembayaran' => 'qris',
                'total_bayar' => 40000,
                'bayar' => 40000,
                'kembalian' => 0,
            ],
        ]);
    }
}
