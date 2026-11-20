<?php

namespace Tests\Feature;

use App\Models\CustomCakeOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomCakeOrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.timezone' => 'Asia/Jakarta']);

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_users');
            $table->string('nama_users');
            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('role')->default('pelanggan');
            $table->string('status')->default('aktif');
        });

        Schema::create('custom_cake_base_prices', function (Blueprint $table) {
            $table->string('ukuran_cm', 10)->primary();
            $table->decimal('harga_dasar', 12, 2);
        });

        DB::table('custom_cake_base_prices')->insert([
            ['ukuran_cm' => '10', 'harga_dasar' => 60000],
            ['ukuran_cm' => '18', 'harga_dasar' => 100000],
            ['ukuran_cm' => '20', 'harga_dasar' => 120000],
        ]);

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->unsignedInteger('id_users')->nullable();
            $table->unsignedInteger('id_customer')->nullable();
            $table->unsignedInteger('id_jadwal')->nullable();
            $table->dateTime('tanggal_pesan')->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->string('status_pesanan')->default('menunggu');
            $table->text('catatan')->nullable();
        });

        Schema::create('custom_cake_orders', function (Blueprint $table) {
            $table->increments('id_custom_order');
            $table->integer('id_users');
            $table->integer('id_pesanan')->nullable()->unique();
            $table->string('ukuran_cm', 10);
            $table->decimal('harga_dasar', 12, 2);
            $table->string('tema', 100);
            $table->string('tulisan', 100);
            $table->text('dekorasi');
            $table->string('foto_referensi')->nullable();
            $table->text('catatan_pelanggan')->nullable();
            $table->decimal('biaya_dekorasi', 12, 2)->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->string('status', 40)->default('menunggu_konfirmasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->increments('id_jadwal');
            $table->date('tanggal_pengambilan');
            $table->time('jam_pengambilan');
            $table->string('status_pengambilan')->default('menunggu');
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_pesanan');
            $table->unsignedInteger('id_produk')->nullable();
            $table->string('ukuran')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_pesanan');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('total_bayar', 12, 2)->default(0);
            $table->decimal('bayar', 12, 2)->default(0);
            $table->decimal('kembalian', 12, 2)->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_bukti')->nullable();
            $table->text('alasan_pengembalian')->nullable();
        });

        Schema::create('riwayat_bukti_pembayaran', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->unsignedInteger('id_pembayaran');
            $table->string('nama_file')->nullable();
            $table->text('alasan_pengembalian')->nullable();
            $table->dateTime('tanggal_upload')->nullable();
            $table->string('status')->nullable();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id_customer');
            $table->string('nomor_telepon')->nullable();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_kue');
            $table->string('status')->default('aktif');
        });

        DB::connection()->getPdo()->sqliteCreateFunction(
            'HOUR',
            static fn ($value) => (int) substr((string) $value, 0, 2)
        );
    }

    public function test_customer_can_submit_custom_design_and_server_sets_base_price(): void
    {
        Storage::fake('local');
        $customer = $this->user();
        $this->actingAs($customer);

        $response = $this->post(route('custom-orders.store'), [
            'ukuran_cm' => '18',
            'harga_dasar' => 1,
            'tema' => 'Pink floral',
            'tulisan' => 'Selamat Ulang Tahun',
            'dekorasi' => 'Bunga buttercream di bagian atas',
            'foto_referensi' => UploadedFile::fake()->createWithContent(
                'referensi.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jwS8AAAAASUVORK5CYII=')
            ),
            'catatan_pelanggan' => 'Warna pastel',
        ]);

        $response->assertRedirect(route('custom-orders.index'));
        $this->assertDatabaseHas('custom_cake_orders', [
            'id_users' => $customer->id_users,
            'ukuran_cm' => '18',
            'harga_dasar' => 100000,
            'tulisan' => 'Selamat Ulang Tahun',
            'biaya_dekorasi' => null,
            'status' => CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
        ]);
        $this->assertNotEmpty(Storage::disk('local')->allFiles('custom-cake-references'));
    }

    public function test_admin_custom_request_list_displays_customer_inscription(): void
    {
        $customer = $this->user();
        $admin = $this->user('admin');
        $this->createRequest($customer);

        $this->actingAs($admin)
            ->get(route('admin.custom-orders.index'))
            ->assertOk()
            ->assertSee('<th>Ukuran</th>', false)
            ->assertSee('<th>Tema/Warna</th>', false)
            ->assertSee('<th>Tulisan</th>', false)
            ->assertSee('class="design-cell"', false)
            ->assertSee('class="unread-dot"', false)
            ->assertSee('Selamat Ulang Tahun');
    }

    public function test_admin_can_update_base_prices_and_new_prices_reach_customer_form_and_request(): void
    {
        $customer = $this->user();
        $existingRequest = $this->createRequest($customer);
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->get(route('admin.custom-orders.index'))
            ->assertOk()
            ->assertSee('Tambah Ukuran')
            ->assertSee('ukuran_baru[]', false)
            ->assertSee('harga_baru[]', false)
            ->assertSee('Hapus')
            ->assertSee(route('admin.custom-orders.base-prices.delete', 10));

        $this->actingAs($admin)
            ->put(route('admin.custom-orders.base-prices.update'), [
                'harga_dasar' => [
                    '10' => '65.000',
                    '18' => '110.000',
                    '20' => '135.000',
                ],
                'ukuran_baru' => ['24'],
                'harga_baru' => ['175.000'],
            ])
            ->assertRedirect(route('admin.custom-orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('custom_cake_base_prices', [
            'ukuran_cm' => '10',
            'harga_dasar' => 65000,
        ]);
        $this->assertDatabaseHas('custom_cake_orders', [
            'id_custom_order' => $existingRequest->id_custom_order,
            'harga_dasar' => 100000,
        ]);

        $this->actingAs($customer)
            ->get(route('custom-orders.create'))
            ->assertOk()
            ->assertSee('data-harga="65000"', false)
            ->assertSee('data-harga="110000"', false)
            ->assertSee('data-harga="135000"', false)
            ->assertSee('24 cm')
            ->assertSee('data-harga="175000"', false);

        $this->post(route('custom-orders.store'), [
            'ukuran_cm' => '24',
            'tema' => 'Pink floral',
            'tulisan' => 'Selamat Ulang Tahun',
            'dekorasi' => 'Bunga buttercream di bagian atas',
        ])->assertRedirect(route('custom-orders.index'));

        $this->assertDatabaseHas('custom_cake_orders', [
            'id_users' => $customer->id_users,
            'ukuran_cm' => '24',
            'harga_dasar' => 175000,
        ]);
    }

    public function test_admin_cannot_add_a_custom_size_that_already_exists(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->put(route('admin.custom-orders.base-prices.update'), [
                'harga_dasar' => [
                    '10' => '60.000',
                    '18' => '100.000',
                    '20' => '120.000',
                ],
                'ukuran_baru' => ['18'],
                'harga_baru' => ['150.000'],
            ])
            ->assertSessionHasErrors('ukuran_baru.0');
    }

    public function test_admin_can_delete_size_without_changing_existing_custom_requests(): void
    {
        $customer = $this->user();
        $existingRequest = $this->createRequest($customer);
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->delete(route('admin.custom-orders.base-prices.delete', 18))
            ->assertRedirect(route('admin.custom-orders.index'));

        $this->assertDatabaseMissing('custom_cake_base_prices', [
            'ukuran_cm' => '18',
        ]);
        $this->assertDatabaseHas('custom_cake_orders', [
            'id_custom_order' => $existingRequest->id_custom_order,
            'ukuran_cm' => '18',
            'harga_dasar' => 100000,
        ]);

        $this->actingAs($customer)
            ->get(route('custom-orders.create'))
            ->assertOk()
            ->assertDontSee('value="18"', false)
            ->assertDontSee('data-harga="100000"', false);

        $this->post(route('custom-orders.store'), [
            'ukuran_cm' => '18',
            'tema' => 'Pink floral',
            'tulisan' => 'Selamat Ulang Tahun',
            'dekorasi' => 'Bunga buttercream di bagian atas',
        ])->assertSessionHasErrors('ukuran_cm');
    }

    public function test_admin_cannot_delete_the_last_custom_size(): void
    {
        $admin = $this->user('admin');
        $this->actingAs($admin);

        $this->delete(route('admin.custom-orders.base-prices.delete', 10));
        $this->delete(route('admin.custom-orders.base-prices.delete', 18));
        $this->delete(route('admin.custom-orders.base-prices.delete', 20))
            ->assertSessionHasErrors('ukuran_cm');

        $this->assertDatabaseHas('custom_cake_base_prices', [
            'ukuran_cm' => '20',
        ]);
    }

    public function test_catalog_exposes_custom_order_form_and_base_price_options(): void
    {
        $customer = $this->user();
        $this->actingAs($customer)
            ->get(route('katalog'))
            ->assertOk()
            ->assertSee('Pesan Kue Custom')
            ->assertSee(route('custom-orders.create'))
            ->assertDontSee(route('custom-orders.index'));

        $this->get(route('custom-orders.create'))
            ->assertOk()
            ->assertSee('10 cm')
            ->assertSee('18 cm')
            ->assertSee('20 cm')
            ->assertSee('data-harga="60000"', false)
            ->assertSee('data-harga="100000"', false)
            ->assertSee('data-harga="120000"', false)
            ->assertSee('id="hargaDasar"', false)
            ->assertDontSee('10 cm — Rp60.000')
            ->assertDontSee('Lihat riwayat permintaan custom')
            ->assertSee('belum termasuk biaya dekorasi');

        $this->get(route('pesanan.pelanggan'))
            ->assertOk()
            ->assertSee('Riwayat Pesanan Custom')
            ->assertSee(route('custom-orders.index'));
    }

    public function test_customer_cannot_view_another_custom_request_or_admin_page(): void
    {
        $owner = $this->user();
        $other = $this->user();
        $this->createRequest($owner);

        $this->actingAs($other)
            ->get(route('custom-orders.index'))
            ->assertOk()
            ->assertDontSee('Pink floral');
        $this->get(route('custom-orders.reference', 1))->assertForbidden();

        $this->get(route('admin.custom-orders.index'))->assertForbidden();
        $this->put(route('admin.custom-orders.base-prices.update'), [
            'harga_dasar' => ['10' => 1, '18' => 1, '20' => 1],
        ])->assertForbidden();
    }

    public function test_approved_custom_cake_is_visible_in_cart_and_counts_toward_two_cake_limit(): void
    {
        $customer = $this->user();
        $custom = $this->createRequest($customer);
        $custom->update([
            'status' => CustomCakeOrder::STATUS_DISETUJUI,
            'biaya_dekorasi' => 25000,
            'total_harga' => 125000,
        ]);

        $this->actingAs($customer);
        session()->put('custom_cake_order_id', $custom->id_custom_order);

        $this->get(route('keranjang.index'))
            ->assertOk()
            ->assertSee('Kue Custom')
            ->assertSee('Rp125.000');

        session()->put('keranjang', [
            '1_1' => ['id_produk' => 1, 'id_ukuran' => 1, 'jumlah' => 1],
            '2_2' => ['id_produk' => 2, 'id_ukuran' => 2, 'jumlah' => 1],
        ]);

        $this->get(route('checkout.keranjang'))
            ->assertRedirect(route('keranjang.index'));
    }

    public function test_admin_must_set_decoration_fee_before_sending_a_quote(): void
    {
        $customer = $this->user();
        $admin = $this->user('admin');
        $custom = $this->createRequest($customer);

        $this->actingAs($admin)
            ->post(route('admin.custom-orders.quote', $custom->id_custom_order), [
                'aksi' => 'tawarkan',
            ])
            ->assertSessionHasErrors('biaya_dekorasi');

        $this->assertDatabaseHas('custom_cake_orders', [
            'id_custom_order' => $custom->id_custom_order,
            'status' => CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
        ]);
    }

    public function test_admin_quote_is_calculated_and_customer_can_checkout_through_existing_flow(): void
    {
        Storage::fake('local');
        Carbon::setTestNow(Carbon::parse('2026-11-20 09:00:00', 'Asia/Jakarta'));

        $customer = $this->user();
        $admin = $this->user('admin');
        $custom = $this->createRequest($customer);

        $this->actingAs($admin)
            ->post(route('admin.custom-orders.quote', $custom->id_custom_order), [
                'aksi' => 'tawarkan',
                'biaya_dekorasi' => '25.000',
                'catatan_admin' => 'Menggunakan dekorasi buttercream.',
            ])
            ->assertRedirect(route('admin.custom-orders.show', $custom->id_custom_order));

        $this->assertDatabaseHas('custom_cake_orders', [
            'id_custom_order' => $custom->id_custom_order,
            'biaya_dekorasi' => 25000,
            'total_harga' => 125000,
            'status' => CustomCakeOrder::STATUS_MENUNGGU_PERSETUJUAN,
        ]);

        $this->actingAs($customer)
            ->post(route('custom-orders.accept', $custom->id_custom_order))
            ->assertRedirect(route('custom-orders.checkout', $custom->id_custom_order));

        $this->get(route('custom-orders.checkout', $custom->id_custom_order))
            ->assertRedirect(route('checkout.keranjang'));
        $this->get(route('checkout.keranjang'))
            ->assertOk()
            ->assertSee('Kue Custom')
            ->assertSee('Harga dasar: Rp100.000')
            ->assertSee('Biaya dekorasi: Rp25.000')
            ->assertSee('Rp125.000');

        $tanggal = Carbon::now('Asia/Jakarta')->addDays(3)->toDateString();
        $this->post(route('checkout.keranjang.proses'), [
            'tanggal_pengambilan' => $tanggal,
            'jam_pengambilan' => '12:00',
            'metode' => 'qris',
        ])->assertRedirect();

        $this->assertDatabaseHas('pesanan', [
            'id_users' => $customer->id_users,
            'total_harga' => 125000,
            'status_pesanan' => 'menunggu',
        ]);

        $savedCustom = CustomCakeOrder::findOrFail($custom->id_custom_order);
        $this->assertNotNull($savedCustom->id_pesanan);
        $this->assertSame(CustomCakeOrder::STATUS_DISETUJUI, $savedCustom->status);
        $this->assertDatabaseHas('pembayaran', [
            'id_pesanan' => $savedCustom->id_pesanan,
            'metode_pembayaran' => 'qris',
            'total_bayar' => 125000,
        ]);
    }

    public function test_custom_orders_are_linked_to_cash_transfer_and_qris_payments(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-20 09:00:00', 'Asia/Jakarta'));
        $admin = $this->user('admin');

        foreach ([
            ['tunai', 'cash'],
            ['transfer', 'transfer'],
            ['qris', 'qris'],
        ] as $index => [$checkoutMethod, $savedMethod]) {
            $customer = $this->user();
            $custom = $this->createRequest($customer);

            $this->actingAs($admin)
                ->post(route('admin.custom-orders.quote', $custom->id_custom_order), [
                    'aksi' => 'tawarkan',
                    'biaya_dekorasi' => '25.000',
                ])
                ->assertRedirect(route('admin.custom-orders.show', $custom->id_custom_order));

            $this->actingAs($customer)
                ->post(route('custom-orders.accept', $custom->id_custom_order))
                ->assertRedirect(route('custom-orders.checkout', $custom->id_custom_order));

            $this->get(route('custom-orders.checkout', $custom->id_custom_order))
                ->assertRedirect(route('checkout.keranjang'));

            $tanggal = Carbon::now('Asia/Jakarta')->addDays($index + 3)->toDateString();
            $this->post(route('checkout.keranjang.proses'), [
                'tanggal_pengambilan' => $tanggal,
                'jam_pengambilan' => '12:00',
                'metode' => $checkoutMethod,
            ])->assertRedirect();

            $custom->refresh();
            $this->assertNotNull($custom->id_pesanan);
            $this->assertDatabaseHas('pembayaran', [
                'id_pesanan' => $custom->id_pesanan,
                'metode_pembayaran' => $savedMethod,
                'total_bayar' => 125000,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.pembayaran'))
            ->assertOk()
            ->assertSee('Rincian Pesanan')
            ->assertSee('Kue Custom')
            ->assertSee('Tulisan: Selamat Ulang Tahun')
            ->assertSee('Cash')
            ->assertSee('Transfer')
            ->assertSee('QRIS');
    }

    public function test_customer_can_resubmit_returned_custom_payment_proof_and_admin_keeps_both_files(): void
    {
        Storage::fake('public');

        $customer = $this->user();
        $admin = $this->user('admin');
        $custom = $this->createRequest($customer);
        $custom->update([
            'status' => CustomCakeOrder::STATUS_DISETUJUI,
            'biaya_dekorasi' => 25000,
            'total_harga' => 125000,
        ]);

        $orderId = DB::table('pesanan')->insertGetId([
            'id_users' => $customer->id_users,
            'total_harga' => 125000,
            'status_pesanan' => 'menunggu',
        ], 'id_pesanan');
        $custom->update(['id_pesanan' => $orderId]);

        $paymentId = DB::table('pembayaran')->insertGetId([
            'id_pesanan' => $orderId,
            'metode_pembayaran' => 'transfer',
            'total_bayar' => 125000,
        ], 'id_pembayaran');

        $this->actingAs($admin)
            ->get(route('admin.pesanan'))
            ->assertOk()
            ->assertSee('class="unread-dot"', false);

        $this->actingAs($customer)
            ->post(route('pesanan.bukti', $orderId), [
                'bukti' => UploadedFile::fake()->createWithContent(
                    'bukti-pertama.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/Yf8AAAAASUVORK5CYII=')
                ),
            ])
            ->assertRedirect(route('pesanan.pelanggan'));

        $buktiPertama = DB::table('pembayaran')
            ->where('id_pembayaran', $paymentId)
            ->value('bukti_pembayaran');

        $this->actingAs($admin)
            ->get(route('admin.pembayaran'))
            ->assertOk()
            ->assertSee('class="unread-dot"', false);

        $this->post(route('admin.pembayaran.kembalikan', $paymentId), [
                'alasan_pengembalian' => 'Foto bukti kurang jelas.',
            ])
            ->assertRedirect(route('admin.pembayaran'));

        $this->actingAs($customer)
            ->get(route('pesanan.pelanggan'))
            ->assertOk()
            ->assertSee('Notifikasi: bukti pembayaran dikembalikan admin.')
            ->assertSee('Unggah Ulang Bukti')
            ->assertSee('Foto bukti kurang jelas.');

        $this->get(route('pelanggan'))
            ->assertOk()
            ->assertSee('aria-label="1 notifikasi pesanan"', false);

        $this->get(route('custom-orders.index'))
            ->assertOk()
            ->assertSee('bukti transfer dikembalikan admin.')
            ->assertSee(route('pesanan.bukti.form', $orderId));

        $this->post(route('pesanan.bukti', $orderId), [
            'bukti' => UploadedFile::fake()->createWithContent(
                'bukti-kedua.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/Yf8AAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('pesanan.pelanggan'));

        $buktiKedua = DB::table('pembayaran')
            ->where('id_pembayaran', $paymentId)
            ->value('bukti_pembayaran');

        $this->assertNotSame($buktiPertama, $buktiKedua);
        $this->assertDatabaseCount('riwayat_bukti_pembayaran', 2);

        $riwayatPertama = DB::table('riwayat_bukti_pembayaran')
            ->where('nama_file', $buktiPertama)
            ->first();
        $riwayatKedua = DB::table('riwayat_bukti_pembayaran')
            ->where('nama_file', $buktiKedua)
            ->first();

        $this->assertSame('dikembalikan', $riwayatPertama->status);
        $this->assertSame('menunggu', $riwayatKedua->status);

        $this->actingAs($admin)
            ->get(route('admin.pembayaran'))
            ->assertOk()
            ->assertSee('Riwayat Bukti (2)')
            ->assertSee(route('admin.pembayaran.riwayatBukti', $riwayatPertama->id_riwayat))
            ->assertSee(route('admin.pembayaran.riwayatBukti', $riwayatKedua->id_riwayat));

        $this->get(route('admin.pembayaran.riwayatBukti', $riwayatPertama->id_riwayat))
            ->assertOk();

        $this->post(route('admin.pembayaran.proses', $paymentId))
            ->assertRedirect(route('admin.pembayaran'));

        $this->assertDatabaseHas('riwayat_bukti_pembayaran', [
            'id_riwayat' => $riwayatPertama->id_riwayat,
            'status' => 'dikembalikan',
        ]);
        $this->assertDatabaseHas('riwayat_bukti_pembayaran', [
            'id_riwayat' => $riwayatKedua->id_riwayat,
            'status' => 'diterima',
        ]);

        $this->get(route('admin.pembayaran'))
            ->assertOk()
            ->assertSee(route('admin.pembayaran.riwayatBukti', $riwayatPertama->id_riwayat))
            ->assertSee(route('admin.pembayaran.riwayatBukti', $riwayatKedua->id_riwayat));
    }

    public function test_customer_cannot_edit_order_after_admin_changes_its_status(): void
    {
        $customer = $this->user();
        $orderId = DB::table('pesanan')->insertGetId([
            'id_users' => $customer->id_users,
            'status_pesanan' => 'dikonfirmasi',
            'total_harga' => 60000,
        ], 'id_pesanan');
        $productId = DB::table('produk')->insertGetId([
            'nama_kue' => 'Kue Ulang Tahun',
            'status' => 'aktif',
        ], 'id_produk');

        $this->actingAs($customer)
            ->get(route('pesanan.edit', $orderId))
            ->assertRedirect(route('pesanan.pelanggan'))
            ->assertSessionHas('error', 'Pesanan tidak dapat diedit karena sudah diproses.');

        $this->put(route('pesanan.update', $orderId), [
            'items' => [
                ['id_produk' => $productId, 'jumlah' => 1],
            ],
            'tanggal_pengambilan' => Carbon::now('Asia/Jakarta')->addDays(3)->toDateString(),
            'jam_pengambilan' => '12:00',
        ])->assertSessionHasErrors('pesanan');

        $this->assertDatabaseHas('pesanan', [
            'id_pesanan' => $orderId,
            'status_pesanan' => 'dikonfirmasi',
            'total_harga' => 60000,
        ]);
    }

    public function test_admin_rejection_requires_and_exposes_a_reason(): void
    {
        $customer = $this->user();
        $admin = $this->user('admin');
        $custom = $this->createRequest($customer);

        $this->actingAs($admin)
            ->post(route('admin.custom-orders.quote', $custom->id_custom_order), [
                'aksi' => 'tolak',
                'catatan_admin' => 'Desain tidak dapat dibuat dengan bahan yang tersedia.',
            ])
            ->assertRedirect(route('admin.custom-orders.show', $custom->id_custom_order));

        $this->actingAs($customer)
            ->get(route('custom-orders.index'))
            ->assertOk()
            ->assertSee('Ditolak')
            ->assertSee('Desain tidak dapat dibuat dengan bahan yang tersedia.');
    }

    private function user(string $role = 'pelanggan'): User
    {
        return User::create([
            'nama_users' => $role === 'admin' ? 'Admin Dwiera' : 'Pelanggan',
            'nomor_telepon' => '08123456789',
            'email' => uniqid().'@example.test',
            'username' => uniqid('user'),
            'password' => 'password',
            'role' => $role,
            'status' => 'aktif',
        ]);
    }

    private function createRequest(User $user): CustomCakeOrder
    {
        return CustomCakeOrder::create([
            'id_users' => $user->id_users,
            'ukuran_cm' => '18',
            'harga_dasar' => 100000,
            'tema' => 'Pink floral',
            'tulisan' => 'Selamat Ulang Tahun',
            'dekorasi' => 'Bunga buttercream di bagian atas',
            'status' => CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
        ]);
    }
}
