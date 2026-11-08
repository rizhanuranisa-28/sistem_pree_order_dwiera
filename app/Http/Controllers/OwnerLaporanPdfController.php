<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerLaporanPdfController extends Controller
{
    public function unduh(Request $request)
    {
        $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
        ], [
            'dari.required' => 'Tanggal awal wajib diisi.',
            'sampai.required' => 'Tanggal akhir wajib diisi.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ]);

        $dari = Carbon::parse($request->dari)->startOfDay();
        $sampai = Carbon::parse($request->sampai)->endOfDay();

        $pesanan = Pesanan::with([
            'user',
            'customer',
            'detailPesanans.produk',
            'pembayaran',
        ])
            ->whereBetween('tanggal_pesan', [$dari, $sampai])
            ->orderBy('tanggal_pesan')
            ->get();

        // Pesanan yang sudah lunas.
        $lunasList = $pesanan->filter(function ($p) {
            $b = $p->pembayaran;

            return $b
                && !empty($b->tanggal_bayar)
                && (float) $b->total_bayar > 0
                && (float) $b->bayar >= (float) $b->total_bayar;
        })->values();

        $belumList = $pesanan->reject(function ($p) use ($lunasList) {
            return $lunasList->contains('id_pesanan', $p->id_pesanan);
        })->values();

        $totalTagihanLunas = (float) $lunasList->sum(
            fn ($p) => (float) $p->pembayaran->total_bayar
        );

        $uangDiterima = (float) $lunasList->sum(
            fn ($p) => (float) $p->pembayaran->bayar
        );

        $totalKembalian = (float) $lunasList->sum(
            fn ($p) => (float) $p->pembayaran->kembalian
        );

        $pendapatanBersih = $uangDiterima - $totalKembalian;

        $belumDibayar = (float) $belumList->sum(function ($p) {
            return (float) ($p->pembayaran->total_bayar ?? $p->total_harga ?? 0);
        });

        // Rekap metode pembayaran dari transaksi lunas.
        $perMetode = [];

        foreach (['cash' => 'Cash', 'transfer' => 'Transfer'] as $kunci => $namaMetode) {
            $kelompok = $lunasList->filter(
                fn ($p) => strtolower(
                    (string) $p->pembayaran->metode_pembayaran
                ) === $kunci
            );

            $perMetode[] = [
                'nama' => $namaMetode,
                'jumlah' => $kelompok->count(),
                'nominal' => (float) $kelompok->sum(
                    fn ($p) => (float) $p->pembayaran->bayar
                        - (float) $p->pembayaran->kembalian
                ),
            ];
        }

        // Rekap produk.
        $produkMap = [];

        foreach (Produk::where('status', 'aktif')->get() as $produk) {
            $produkMap[$produk->nama_kue] = [
                'nama' => $produk->nama_kue,
                'jumlah' => 0,
                'pendapatan' => 0,
            ];
        }

        foreach ($lunasList as $p) {
            foreach ($p->detailPesanans as $d) {
                $namaProduk = $d->produk->nama_kue ?? 'Produk dihapus';

                if (!isset($produkMap[$namaProduk])) {
                    $produkMap[$namaProduk] = [
                        'nama' => $namaProduk,
                        'jumlah' => 0,
                        'pendapatan' => 0,
                    ];
                }

                $produkMap[$namaProduk]['jumlah'] += (int) $d->jumlah;
                $produkMap[$namaProduk]['pendapatan'] += (float) $d->subtotal;
            }
        }

        $produkUrut = collect($produkMap)->sortByDesc('jumlah')->values();

        $terlaris = $produkUrut
            ->filter(fn ($x) => $x['jumlah'] > 0)
            ->take(5)
            ->values();

        $kurangLaku = collect($produkMap)
            ->sortBy('jumlah')
            ->take(5)
            ->values();

        $maksJumlah = max(1, (int) $produkUrut->max('jumlah'));
        $totalKueTerjual = (int) $produkUrut->sum('jumlah');

        // Rekap status pesanan.
        $statusNama = [
            'menunggu' => 'Menunggu',
            'dikonfirmasi' => 'Dikonfirmasi',
            'dibuat' => 'Sedang Dibuat',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
        ];

        $rekapStatus = [];

        foreach ($statusNama as $kunci => $namaStatus) {
            $rekapStatus[] = [
                'nama' => $namaStatus,
                'jumlah' => $pesanan->where('status_pesanan', $kunci)->count(),
            ];
        }

        /*
         * Rekap pelanggan:
         * - Hanya pesanan lunas yang dihitung.
         * - Jumlah transaksi dihitung dalam periode laporan terpilih.
         * - Member dan customer dipisahkan.
         */
        $buatPredikat = function ($jumlah) {
            if ($jumlah >= 5) {
                return 'Pelanggan Tetap';
            }

            if ($jumlah >= 2) {
                return 'Pelanggan Aktif';
            }

            return 'Pelanggan Baru';
        };

        $rekapMember = $lunasList
            ->filter(fn ($p) => !empty($p->id_users))
            ->groupBy('id_users')
            ->map(function ($transaksi) use ($buatPredikat) {
                $pelanggan = $transaksi->first()->user;
                $jumlah = $transaksi->count();

                return [
                    'id' => $pelanggan->id_users ?? null,
                    'nama' => $pelanggan->nama_users ?? 'Member tidak diketahui',
                    'telepon' => $pelanggan->nomor_telepon ?? '-',
                    'jumlah_transaksi' => $jumlah,
                    'predikat' => $buatPredikat($jumlah),
                ];
            })
            ->sortByDesc('jumlah_transaksi')
            ->values();

        $rekapCustomer = $lunasList
            ->filter(fn ($p) => !empty($p->id_customer))
            ->groupBy('id_customer')
            ->map(function ($transaksi) use ($buatPredikat) {
                $pelanggan = $transaksi->first()->customer;
                $jumlah = $transaksi->count();

                return [
                    'id' => $pelanggan->id_customer ?? null,
                    'nama' => $pelanggan->nama_customer ?? 'Customer tidak diketahui',
                    'telepon' => $pelanggan->nomor_telepon ?? '-',
                    'jumlah_transaksi' => $jumlah,
                    'predikat' => $buatPredikat($jumlah),
                ];
            })
            ->sortByDesc('jumlah_transaksi')
            ->values();

        $data = [
            'dari' => $dari,
            'sampai' => $sampai,
            'dicetak' => now(),

            'pesanan' => $pesanan,
            'lunasList' => $lunasList,

            'totalPesanan' => $pesanan->count(),
            'jumlahLunas' => $lunasList->count(),
            'totalTagihanLunas' => $totalTagihanLunas,
            'uangDiterima' => $uangDiterima,
            'totalKembalian' => $totalKembalian,
            'pendapatanBersih' => $pendapatanBersih,
            'belumDibayar' => $belumDibayar,

            'perMetode' => $perMetode,
            'terlaris' => $terlaris,
            'kurangLaku' => $kurangLaku,
            'maksJumlah' => $maksJumlah,
            'totalKueTerjual' => $totalKueTerjual,
            'rekapStatus' => $rekapStatus,

            'rekapMember' => $rekapMember,
            'rekapCustomer' => $rekapCustomer,
        ];

        $namaFile = 'laporan-penjualan-'
            . $dari->format('Ymd')
            . '-'
            . $sampai->format('Ymd')
            . '.pdf';

        return Pdf::loadView('pdf.laporan_penjualan', $data)
            ->setPaper('a4', 'portrait')
            ->download($namaFile);
    }
}
