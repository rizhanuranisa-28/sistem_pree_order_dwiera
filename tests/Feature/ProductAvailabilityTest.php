<?php

namespace Tests\Feature;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DetailProdukController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PreOrderController;
use App\Support\BatasJumlahPesanan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class ProductAvailabilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_kue');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->string('ukuran')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status', 30);
        });

        Schema::create('ukuran_produk', function (Blueprint $table) {
            $table->increments('id_ukuran');
            $table->unsignedInteger('id_produk');
            $table->string('nama_ukuran');
            $table->decimal('harga', 12, 2);
            $table->string('status', 20);
        });

        DB::table('produk')->insert([
            [
                'id_produk' => 1,
                'nama_kue' => 'Kue Tersedia',
                'status' => 'tersedia',
            ],
            [
                'id_produk' => 2,
                'nama_kue' => 'Kue Aktif Lama',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 3,
                'nama_kue' => 'Kue Habis',
                'status' => 'habis',
            ],
        ]);

        DB::table('ukuran_produk')->insert([
            [
                'id_produk' => 1,
                'nama_ukuran' => 'Kecil',
                'harga' => 100000,
                'status' => 'tersedia',
            ],
            [
                'id_produk' => 1,
                'nama_ukuran' => 'Sedang',
                'harga' => 150000,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 1,
                'nama_ukuran' => 'Besar',
                'harga' => 200000,
                'status' => 'habis',
            ],
        ]);
    }

    public function test_customer_catalog_shows_available_products_and_hides_sold_out_products(): void
    {
        $view = app(KatalogController::class)->index();
        $productNames = $view->getData()['produk']->pluck('nama_kue')->all();

        $this->assertEqualsCanonicalizing(
            ['Kue Tersedia', 'Kue Aktif Lama'],
            $productNames
        );
        $this->assertNotContains('Kue Habis', $productNames);
    }

    public function test_preorder_only_offers_available_sizes(): void
    {
        $view = app(PreOrderController::class)->create(1);
        $sizes = $view->getData()['produk']->ukuranProduks
            ->pluck('nama_ukuran')
            ->all();

        $this->assertEqualsCanonicalizing(['Kecil', 'Sedang'], $sizes);
        $this->assertNotContains('Besar', $sizes);
    }

    public function test_product_detail_shows_size_without_price_in_selector(): void
    {
        $view = app(DetailProdukController::class)->show(1);
        $view->with('errors', new ViewErrorBag());
        $html = $view->render();

        $this->assertStringContainsString('Kecil', $html);
        $this->assertStringNotContainsString('Rp100.000', $html);
        $this->assertStringContainsString('data-harga="100000"', $html);
        $this->assertStringContainsString('max="2"', $html);
        $this->assertStringContainsString('Maksimal pemesanan 2 kue', $html);
        $this->assertStringContainsString('const total = harga * jumlahDipilih;', $html);
        $this->assertStringContainsString("jumlah.addEventListener('input', validasiJumlah);", $html);
    }

    public function test_product_checkout_rejects_more_than_two_cakes(): void
    {
        $request = Request::create('/checkout/1', 'GET', [
            'id_ukuran' => 1,
            'jumlah' => 3,
        ]);

        $response = app(CheckoutController::class)->create($request, 1);

        $this->assertSame(
            route('preorder.create', 1),
            $response->getTargetUrl()
        );
    }

    public function test_cart_checkout_rejects_more_than_two_total_cakes(): void
    {
        session()->put('keranjang', [
            '1_1' => [
                'id_produk' => 1,
                'id_ukuran' => 1,
                'jumlah' => 2,
            ],
            '1_2' => [
                'id_produk' => 1,
                'id_ukuran' => 2,
                'jumlah' => 1,
            ],
        ]);

        $response = app(CheckoutController::class)->keranjang();

        $this->assertSame(
            route('keranjang.index'),
            $response->getTargetUrl()
        );
        $this->assertSame(
            3,
            BatasJumlahPesanan::total(session()->get('keranjang'))
        );
    }

    public function test_cart_checkout_sums_prices_for_two_cakes(): void
    {
        session()->put('keranjang', [
            '1_1' => [
                'id_produk' => 1,
                'id_ukuran' => 1,
                'jumlah' => 1,
            ],
            '1_2' => [
                'id_produk' => 1,
                'id_ukuran' => 2,
                'jumlah' => 1,
            ],
        ]);

        $view = app(CheckoutController::class)->keranjang();

        $this->assertSame(250000.0, $view->getData()['total']);
    }

    public function test_cart_add_rejects_more_than_two_total_cakes(): void
    {
        session()->put('keranjang', [
            '1_1' => [
                'id_produk' => 1,
                'id_ukuran' => 1,
                'jumlah' => 2,
            ],
        ]);

        $request = Request::create('/keranjang/tambah/1', 'POST', [
            'id_ukuran' => 2,
            'jumlah' => 1,
        ]);

        app(KeranjangController::class)->tambah($request, 1);

        $this->assertSame(
            2,
            BatasJumlahPesanan::total(session()->get('keranjang'))
        );
    }

    public function test_cart_submission_rejects_more_than_two_total_cakes(): void
    {
        session()->put('keranjang', [
            '1_1' => [
                'id_produk' => 1,
                'id_ukuran' => 1,
                'jumlah' => 2,
            ],
            '1_2' => [
                'id_produk' => 1,
                'id_ukuran' => 2,
                'jumlah' => 1,
            ],
        ]);

        $request = Request::create('/checkout-keranjang', 'POST', [
            'tanggal_pengambilan' => now()->addDay()->toDateString(),
            'jam_pengambilan' => '12:00',
            'metode' => 'cash',
        ]);

        $response = app(CheckoutController::class)->prosesKeranjang($request);

        $this->assertSame(
            route('keranjang.index'),
            $response->getTargetUrl()
        );
    }
}
