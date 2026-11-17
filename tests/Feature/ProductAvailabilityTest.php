<?php

namespace Tests\Feature;

use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PreOrderController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
}
