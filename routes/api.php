<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;

Route::get('/health', [HealthController::class, 'index']);

Route::get('/produk', [ProdukController::class, 'index']);

Route::get('/pesanan', [PesananController::class, 'index']);

Route::get('/pembayaran', [PembayaranController::class, 'index']);