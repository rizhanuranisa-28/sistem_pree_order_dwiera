<?php

namespace App\Providers;

use App\Models\CustomCakeOrder;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'partials.mobile_bottom_nav',
            'partials.role_sidebar',
            'admin',
            'admin_pesanan',
            'admin_pembayaran',
            'admin_produk',
            'admin_produk_tambah',
            'pelanggan',
            'katalog',
            'detail_produk',
            'pesanan_pelanggan',
        ], function ($view): void {
            $user = Auth::user();
            $adminCounts = [
                'adminUnreadOrders' => 0,
                'adminUnreadCustomOrders' => 0,
                'adminUnreadPayments' => 0,
            ];

            if ($user && $user->role === 'admin') {
                $adminCounts = [
                    'adminUnreadOrders' => Pesanan::whereIn(
                        'status_pesanan',
                        ['menunggu', 'menunggu konfirmasi']
                    )->count(),
                    'adminUnreadCustomOrders' => CustomCakeOrder::where(
                        'status',
                        CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI
                    )->count(),
                    'adminUnreadPayments' => Pembayaran::whereIn(
                        'metode_pembayaran',
                        ['transfer', 'qris']
                    )
                        ->whereNull('tanggal_bayar')
                        ->whereNotNull('bukti_pembayaran')
                        ->where('bukti_pembayaran', '!=', '')
                        ->where(function ($query) {
                            $query->whereNull('status_bukti')
                                ->orWhere('status_bukti', 'menunggu');
                        })
                        ->count(),
                ];
            }

            $view->with($adminCounts);
            $view->with([
                'customerUnreadCount' => 0,
                'customerUnreadPaymentReturns' => 0,
                'customerUnreadCustomActions' => 0,
            ]);

            if ($user && $user->role === 'pelanggan') {
                $paymentReturns = Pembayaran::whereNull('tanggal_bayar')
                    ->where('status_bukti', 'dikembalikan')
                    ->whereHas('pesanan', function ($query) use ($user) {
                        $query->where('id_users', $user->id_users);
                    })
                    ->count();

                $customActions = CustomCakeOrder::where(
                    'id_users',
                    $user->id_users
                )
                    ->whereIn('status', [
                        CustomCakeOrder::STATUS_MENUNGGU_PERSETUJUAN,
                        CustomCakeOrder::STATUS_DISETUJUI,
                    ])
                    ->whereNull('id_pesanan')
                    ->count();

                $view->with([
                    'customerUnreadCount' => $paymentReturns + $customActions,
                    'customerUnreadPaymentReturns' => $paymentReturns,
                    'customerUnreadCustomActions' => $customActions,
                ]);
            }
        });
    }
}
