<?php

namespace App\Support;

use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerSalesReport
{
    public function filters(Request $request): array
    {
        $today = now(config('app.timezone', 'Asia/Jakarta'));

        $request->merge([
            'dari' => $request->query('dari') ?: $today->copy()->startOfMonth()->toDateString(),
            'sampai' => $request->query('sampai') ?: $today->toDateString(),
        ]);

        return $request->validate([
            'dari' => ['required', 'date_format:Y-m-d'],
            'sampai' => ['required', 'date_format:Y-m-d', 'after_or_equal:dari'],
            'metode_pembayaran' => [
                'nullable',
                'in:cash,qris,transfer',
            ],
            'status_pesanan' => [
                'nullable',
                'in:menunggu,dikonfirmasi,dibuat,siap_diambil,selesai',
            ],
        ], [
            'dari.required' => 'Tanggal awal wajib diisi.',
            'sampai.required' => 'Tanggal akhir wajib diisi.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ]);
    }

    public function orders(array $filters): Collection
    {
        $zonaWaktu = config('app.timezone', 'Asia/Jakarta');
        $dari = Carbon::parse($filters['dari'], $zonaWaktu)->startOfDay();
        $sampai = Carbon::parse($filters['sampai'], $zonaWaktu)->endOfDay();
        $tanggalTransaksi = 'COALESCE(pembayaran.tanggal_bayar, pesanan.tanggal_pesan)';

        return Pesanan::query()
            ->leftJoin('pembayaran', 'pembayaran.id_pesanan', '=', 'pesanan.id_pesanan')
            ->select('pesanan.*')
            ->selectRaw($tanggalTransaksi . ' AS tanggal_transaksi')
            ->with([
                'user',
                'customer',
                'detailPesanans.produk',
                'pembayaran',
            ])
            ->whereBetween(DB::raw($tanggalTransaksi), [$dari, $sampai])
            ->when(
                !empty($filters['metode_pembayaran']),
                fn ($query) => $query->where(
                    'pembayaran.metode_pembayaran',
                    $filters['metode_pembayaran']
                )
            )
            ->when(
                !empty($filters['status_pesanan']),
                fn ($query) => $query->where(
                    'pesanan.status_pesanan',
                    $filters['status_pesanan']
                )
            )
            ->orderBy('tanggal_transaksi')
            ->orderBy('pesanan.id_pesanan')
            ->get();
    }

    public function totalPenjualan(Collection $pesanan): float
    {
        return (float) $pesanan->sum(function ($item) {
            $pembayaran = $item->pembayaran;

            if (!$pembayaran || empty($pembayaran->tanggal_bayar)) {
                return 0;
            }

            return (float) $pembayaran->bayar - (float) $pembayaran->kembalian;
        });
    }
}
