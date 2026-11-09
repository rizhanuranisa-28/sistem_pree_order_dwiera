<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRole;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\DetailProdukController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AdminPesananController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminPembayaranController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\OwnerPesananController;
use App\Http\Controllers\OwnerLaporanController;
use App\Http\Controllers\OwnerGrafikController;
use App\Http\Controllers\OwnerAdminController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\LupaPasswordController;
use App\Http\Controllers\OwnerProdukController;
use App\Http\Controllers\BuktiPembayaranController;
use App\Http\Controllers\OwnerLaporanPdfController;
use App\Http\Controllers\AdminTambahPesananController;


/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');


/*
|--------------------------------------------------------------------------
| Login, Register, Lupa Password & Logout
|--------------------------------------------------------------------------
*/

Route::view('/login', 'login')->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::get('/lupa-password', [LupaPasswordController::class, 'form'])
    ->name('password.request');

Route::post('/lupa-password', [LupaPasswordController::class, 'kirimLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [LupaPasswordController::class, 'formReset'])
    ->name('password.reset');

Route::post('/reset-password/{token}', [LupaPasswordController::class, 'simpanPassword'])
    ->name('password.update');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Area Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', CekRole::class . ':admin'])->group(function () {

    Route::view('/admin', 'admin')->name('admin');

    // Pesanan Admin
    Route::get('/admin/pesanan', [AdminPesananController::class, 'index'])
        ->name('admin.pesanan');

    Route::get('/admin/pesanan/tambah', [AdminTambahPesananController::class, 'create'])
        ->name('admin.pesanan.create');

    Route::post('/admin/pesanan/tambah', [AdminTambahPesananController::class, 'store'])
        ->name('admin.pesanan.store');

    Route::post('/admin/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])
        ->name('admin.pesanan.status');

    Route::get('/admin/pesanan/{id}/struk', [StrukController::class, 'admin'])
        ->name('struk.admin');


    // Pembayaran Admin
    Route::get('/admin/pembayaran', [AdminPembayaranController::class, 'index'])
        ->name('admin.pembayaran');

    Route::post('/admin/pembayaran/{id}/proses', [AdminPembayaranController::class, 'proses'])
        ->name('admin.pembayaran.proses');

    Route::post('/admin/pembayaran/{id}/upload-bukti', [AdminPembayaranController::class, 'uploadBukti'])
        ->name('admin.pembayaran.uploadBukti');

    Route::post('/admin/pembayaran/{id}/kembalikan', [AdminPembayaranController::class, 'kembalikan'])
        ->name('admin.pembayaran.kembalikan');


    // Produk Admin
    Route::get('/admin/produk', [AdminProdukController::class, 'index'])
        ->name('admin.produk');

    Route::get('/admin/produk/tambah', [AdminProdukController::class, 'create'])
        ->name('admin.produk.create');

    Route::post('/admin/produk', [AdminProdukController::class, 'store'])
        ->name('admin.produk.store');

    Route::get('/admin/produk/{id}/edit', [AdminProdukController::class, 'edit'])
        ->name('admin.produk.edit');

    Route::put('/admin/produk/{id}', [AdminProdukController::class, 'update'])
        ->name('admin.produk.update');

    Route::delete('/admin/produk/{id}', [AdminProdukController::class, 'destroy'])
        ->name('admin.produk.destroy');
});


/*
|--------------------------------------------------------------------------
| Area Owner
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', CekRole::class . ':owner'])->group(function () {

    Route::get('/owner', [OwnerDashboardController::class, 'index'])
        ->name('owner');

    Route::get('/owner/pesanan', [OwnerPesananController::class, 'index'])
        ->name('owner.pesanan');

    Route::get('/owner/produk', [OwnerProdukController::class, 'index'])
        ->name('owner.produk');

    Route::get('/owner/laporan', [OwnerLaporanController::class, 'index'])
        ->name('owner.laporan');

    Route::get('/owner/laporan/pdf', [OwnerLaporanPdfController::class, 'unduh'])
        ->name('owner.laporan.pdf');

    Route::get('/owner/grafik', [OwnerGrafikController::class, 'index'])
        ->name('owner.grafik');

    // Manajemen Admin
    Route::get('/owner/admin', [OwnerAdminController::class, 'index'])
        ->name('owner.admin');

    Route::post('/owner/admin', [OwnerAdminController::class, 'store'])
        ->name('owner.admin.store');

    Route::put('/owner/admin/{id}/password', [OwnerAdminController::class, 'resetPassword'])
        ->name('owner.admin.password');

    // Aktifkan / Nonaktifkan Akun Admin
    Route::patch('/owner/admin/{id}/status', [OwnerAdminController::class, 'toggleStatus'])
        ->name('owner.admin.status');
});

/*
|--------------------------------------------------------------------------
| Dashboard Pelanggan
|--------------------------------------------------------------------------
*/

Route::get('/pelanggan', function () {

    $produk = \App\Models\Produk::where('status', 'aktif')
        ->latest('id_produk')
        ->get();

    return view('pelanggan', compact('produk'));

})->middleware('auth')->name('pelanggan');


/*
|--------------------------------------------------------------------------
| Katalog dan Detail Produk
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/katalog', [KatalogController::class, 'index'])
        ->name('katalog');

    Route::get('/produk/{id}', [DetailProdukController::class, 'show'])
        ->name('produk.detail');

});


/*
|--------------------------------------------------------------------------
| Pre-Order Lama
|--------------------------------------------------------------------------
*/

Route::get('/pre-order/{id}', [PreOrderController::class, 'create'])
    ->middleware('auth')
    ->name('preorder.create');


/*
|--------------------------------------------------------------------------
| Keranjang dan Checkout Beberapa Produk
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/keranjang', [KeranjangController::class, 'index'])
        ->name('keranjang.index');

    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])
        ->name('keranjang.tambah');

    Route::post('/keranjang/update/{id}', [KeranjangController::class, 'update'])
        ->name('keranjang.update');

    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])
        ->name('keranjang.hapus');

    Route::delete('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])
        ->name('keranjang.kosongkan');

    Route::get('/checkout-keranjang', [CheckoutController::class, 'keranjang'])
        ->name('checkout.keranjang');

    Route::post('/checkout-keranjang', [CheckoutController::class, 'prosesKeranjang'])
        ->name('checkout.keranjang.proses');

    Route::get('/pesanan-saya', [PesananController::class, 'pelanggan'])
        ->name('pesanan.pelanggan');

    Route::get('/pesanan-saya/{id}/edit', [PesananController::class, 'edit'])
        ->name('pesanan.edit');

    Route::put('/pesanan-saya/{id}', [PesananController::class, 'update'])
        ->name('pesanan.update');

    Route::delete('/pesanan-saya/{id}/batal', [PesananController::class, 'batal'])
        ->name('pesanan.batal');

    Route::post('/pesanan-saya/{id}/bukti', [BuktiPembayaranController::class, 'upload'])
        ->name('pesanan.bukti');

    Route::get('/pesanan-saya/{id}/bukti', [BuktiPembayaranController::class, 'form'])
        ->name('pesanan.bukti.form');

    Route::get('/pesanan-saya/{id}/selesai', [BuktiPembayaranController::class, 'selesai'])
        ->name('pesanan.bukti.selesai');

    Route::get('/pesanan-saya/{id}/struk', [StrukController::class, 'pelanggan'])
        ->name('struk.pelanggan');

    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil');

    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');
});


/*
|--------------------------------------------------------------------------
| Checkout dan Pembayaran Satu Produk (Alur Lama)
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{id}', [CheckoutController::class, 'create'])
    ->middleware('auth')
    ->name('checkout.create');

Route::get('/pembayaran/{id}', [PembayaranController::class, 'create'])
    ->middleware('auth')
    ->name('pembayaran.create');

Route::post('/pembayaran/{id}/konfirmasi', [PembayaranController::class, 'konfirmasi'])
    ->middleware('auth')
    ->name('pembayaran.konfirmasi');