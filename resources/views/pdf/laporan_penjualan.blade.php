<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Dwiera Cake</title>

    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 11px;
            color: #333;
        }

        h1 {
            font-size: 20px;
            color: #c9577c;
            margin: 0 0 5px;
            text-align: center;
        }

        h2 {
            font-size: 14px;
            color: #c9577c;
            margin: 22px 0 8px;
        }

        .sub {
            color: #777;
            font-size: 10px;
            margin-bottom: 4px;
        }

        /* HEADER PDF */
        .header-pdf {
            text-align: center;
            border-bottom: 2px solid #e9a0b5;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .logo-dwiera {
            width: 85px;
            height: auto;
            margin-bottom: 8px;
        }

        .header-pdf .sub {
            text-align: center;
        }

        /* ALAMAT TOKO */
        .alamat-toko {
            color: #777;
            font-size: 9px;
            line-height: 1.5;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8dce6;
            color: #555;
            text-align: left;
            padding: 7px 6px;
            font-size: 9px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            font-size: 9px;
            vertical-align: top;
        }

        .ringkas td {
            font-size: 11px;
            padding: 7px 6px;
        }

        .ringkas td.nilai {
            text-align: right;
            font-weight: bold;
        }

        .utama td {
            background: #fff0f4;
            color: #c9577c;
            font-size: 12px;
            font-weight: bold;
        }

        .kanan {
            text-align: right;
        }

        .tengah {
            text-align: center;
        }

        .bar-wrap {
            background: #f6eef1;
            height: 12px;
        }

        .bar {
            background: #d46a8c;
            height: 12px;
        }

        .bar-kurang {
            background: #8aa4d6;
            height: 12px;
        }

        .kosong {
            color: #999;
            text-align: center;
            padding: 12px;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd td {
            border: none;
            font-size: 11px;
        }

        .footer {
            margin-top: 18px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }

        /* PREDIKAT PELANGGAN */
        .pelanggan-tetap {
            color: #228b57;
            font-weight: bold;
        }

        .pelanggan-aktif {
            color: #b7791f;
            font-weight: bold;
        }

        .pelanggan-baru {
            color: #777;
            font-weight: bold;
        }

        .catatan-pelanggan {
            font-size: 9px;
            color: #777;
            margin-top: 5px;
        }
    </style>
</head>

<body>

@php
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

{{-- HEADER LAPORAN --}}
<div class="header-pdf">

    @if (file_exists(public_path('images/logo_dwiera.jpeg')))
        <img
            src="{{ public_path('images/logo_dwiera.jpeg') }}"
            style="width: 85px; height: auto;"
            alt="Logo Dwiera Cake"
        >
    @endif

    <h1>LAPORAN PENJUALAN DWIÉRA CAKE</h1>

    <div class="sub">
        <strong>Laporan Penjualan dan Keuangan</strong>
    </div>

    {{-- ALAMAT DWIERA CAKE --}}
    <div class="alamat-toko">
        Jl. Babakan Tiga, Panyocokan,<br>
        Kec. Ciwidey, Kabupaten Bandung,<br>
        Jawa Barat 40971
    </div>

    <div class="sub">
        Periode:
        {{ $dari->format('d/m/Y') }}
        sampai
        {{ $sampai->format('d/m/Y') }}
    </div>

    <div class="sub">
        Dicetak: {{ $dicetak->format('d/m/Y H:i') }}
    </div>

</div>

{{-- RINGKASAN KEUANGAN --}}
<h2>Ringkasan Keuangan</h2>

<table class="ringkas">
    <tr>
        <td>Total pesanan pada periode</td>
        <td class="nilai">{{ $totalPesanan }}</td>
    </tr>

    <tr>
        <td>Pesanan lunas</td>
        <td class="nilai">{{ $jumlahLunas }}</td>
    </tr>

    <tr>
        <td>Total kue terjual (pesanan lunas)</td>
        <td class="nilai">{{ $totalKueTerjual }}</td>
    </tr>

    <tr>
        <td>Total tagihan pesanan lunas</td>
        <td class="nilai">{{ $rp($totalTagihanLunas) }}</td>
    </tr>

    <tr>
        <td>Uang diterima</td>
        <td class="nilai">{{ $rp($uangDiterima) }}</td>
    </tr>

    <tr>
        <td>Total kembalian</td>
        <td class="nilai">{{ $rp($totalKembalian) }}</td>
    </tr>

    <tr class="utama">
        <td>Pendapatan bersih (uang diterima dikurangi kembalian)</td>
        <td class="nilai">{{ $rp($pendapatanBersih) }}</td>
    </tr>

    <tr>
        <td>Belum dibayar (pesanan belum lunas)</td>
        <td class="nilai">{{ $rp($belumDibayar) }}</td>
    </tr>
</table>

{{-- PENDAPATAN PER METODE PEMBAYARAN --}}
<h2>Pendapatan per Metode Pembayaran</h2>

<table>
    <thead>
        <tr>
            <th>Metode</th>
            <th class="tengah">Jumlah Transaksi</th>
            <th class="kanan">Pendapatan</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($perMetode as $m)
            <tr>
                <td>{{ $m['nama'] }}</td>
                <td class="tengah">{{ $m['jumlah'] }}</td>
                <td class="kanan">{{ $rp($m['nominal']) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- GRAFIK PRODUK TERLARIS --}}
<h2>Grafik Produk Terlaris</h2>

@if ($terlaris->count() > 0)
    <table>
        @foreach ($terlaris as $item)
            <tr>
                <td style="width: 32%; border: none;">
                    {{ $item['nama'] }}
                </td>

                <td style="border: none;">
                    <div class="bar-wrap">
                        <div
                            class="bar"
                            style="width: {{ max(3, round($item['jumlah'] / $maksJumlah * 100)) }}%;"
                        ></div>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@else
    <div class="kosong">
        Belum ada penjualan lunas pada periode ini.
    </div>
@endif

{{-- GRAFIK PRODUK KURANG TERJUAL --}}
<h2>Grafik Produk Kurang Terjual</h2>

<table>
    @foreach ($kurangLaku as $item)
        <tr>
            <td style="width: 32%; border: none;">
                {{ $item['nama'] }}
            </td>

            <td style="border: none;">
                <div class="bar-wrap">
                    <div
                        class="bar-kurang"
                        style="width: {{ max(3, round($item['jumlah'] / $maksJumlah * 100)) }}%;"
                    ></div>
                </div>
            </td>
        </tr>
    @endforeach
</table>

{{-- RINCIAN PRODUK TERLARIS --}}
<h2>Rincian Produk</h2>

<table>
    <thead>
        <tr>
            <th>Produk Terlaris</th>
            <th class="tengah">Terjual</th>
            <th class="kanan">Pendapatan</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($terlaris as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td class="tengah">{{ $item['jumlah'] }}</td>
                <td class="kanan">{{ $rp($item['pendapatan']) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="kosong">Belum ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>

{{-- RINCIAN PRODUK KURANG TERJUAL --}}
<table>
    <thead>
        <tr>
            <th>Produk Kurang Terjual</th>
            <th class="tengah">Terjual</th>
            <th class="kanan">Pendapatan</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($kurangLaku as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td class="tengah">{{ $item['jumlah'] }}</td>
                <td class="kanan">{{ $rp($item['pendapatan']) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="kosong">Belum ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- REKAP STATUS PESANAN --}}
<h2>Rekap Status Pesanan</h2>

<table>
    <thead>
        <tr>
            <th>Status</th>
            <th class="tengah">Jumlah Pesanan</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($rekapStatus as $s)
            <tr>
                <td>{{ $s['nama'] }}</td>
                <td class="tengah">{{ $s['jumlah'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- REKAP MEMBER --}}
<h2>Rekap Pelanggan Member</h2>

<table>
    <thead>
        <tr>
            <th style="width: 6%;">No.</th>
            <th style="width: 27%;">Nama Member</th>
            <th style="width: 23%;">Nomor Telepon</th>
            <th class="tengah" style="width: 19%;">Jumlah Transaksi</th>
            <th style="width: 25%;">Predikat</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($rekapMember as $index => $member)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $member['nama'] }}</td>
                <td>{{ $member['telepon'] }}</td>
                <td class="tengah">{{ $member['jumlah_transaksi'] }}</td>
                <td>
                    @if ($member['jumlah_transaksi'] >= 5)
                        <span class="pelanggan-tetap">Pelanggan Tetap</span>
                    @elseif ($member['jumlah_transaksi'] >= 2)
                        <span class="pelanggan-aktif">Pelanggan Aktif</span>
                    @else
                        <span class="pelanggan-baru">Pelanggan Baru</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="kosong">
                    Belum ada transaksi member yang lunas pada periode ini.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- REKAP CUSTOMER NON-MEMBER --}}
<h2>Rekap Pelanggan Non-Member</h2>

<table>
    <thead>
        <tr>
            <th style="width: 6%;">No.</th>
            <th style="width: 27%;">Nama Customer</th>
            <th style="width: 23%;">Nomor Telepon</th>
            <th class="tengah" style="width: 19%;">Jumlah Transaksi</th>
            <th style="width: 25%;">Predikat</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($rekapCustomer as $index => $customer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $customer['nama'] }}</td>
                <td>{{ $customer['telepon'] }}</td>
                <td class="tengah">{{ $customer['jumlah_transaksi'] }}</td>
                <td>
                    @if ($customer['jumlah_transaksi'] >= 5)
                        <span class="pelanggan-tetap">Pelanggan Tetap</span>
                    @elseif ($customer['jumlah_transaksi'] >= 2)
                        <span class="pelanggan-aktif">Pelanggan Aktif</span>
                    @else
                        <span class="pelanggan-baru">Pelanggan Baru</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="kosong">
                    Belum ada transaksi customer yang lunas pada periode ini.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="catatan-pelanggan">
    Predikat pelanggan dihitung dari jumlah transaksi lunas dalam periode laporan:
    1 transaksi = Pelanggan Baru, 2–4 transaksi = Pelanggan Aktif,
    dan 5 transaksi atau lebih = Pelanggan Tetap.
</div>

{{-- RINCIAN TRANSAKSI LUNAS --}}
<h2>Rincian Transaksi Lunas</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal Bayar</th>
            <th>No. Pesanan</th>
            <th>Pelanggan</th>
            <th>Metode</th>
            <th class="kanan">Tagihan</th>
            <th class="kanan">Diterima</th>
            <th class="kanan">Kembalian</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($lunasList as $p)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($p->pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
                </td>

                <td>
                    #DW{{ str_pad($p->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                </td>

                <td>
                    @if ($p->user)
                        {{ $p->user->nama_users }}
                        <br>
                        <span style="color: #777;">Member</span>
                    @elseif ($p->customer)
                        {{ $p->customer->nama_customer }}
                        <br>
                        <span style="color: #777;">Non-Member</span>
                    @else
                        -
                    @endif
                </td>

                <td>
                    {{ ucfirst($p->pembayaran->metode_pembayaran) }}
                </td>

                <td class="kanan">
                    {{ $rp($p->pembayaran->total_bayar) }}
                </td>

                <td class="kanan">
                    {{ $rp($p->pembayaran->bayar) }}
                </td>

                <td class="kanan">
                    {{ $rp($p->pembayaran->kembalian) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="kosong">
                    Belum ada transaksi lunas pada periode ini.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- TANDA TANGAN OWNER --}}
<table class="ttd">
    <tr>
        <td style="width: 60%;"></td>

        <td class="tengah">
            Owner Dwiera Cake
            <br><br><br><br>
            ( ______________________ )
        </td>
    </tr>
</table>

<div class="footer">
    Dokumen ini dibuat otomatis oleh Sistem Pre-Order Dwiera Cake.
</div>

</body>
</html>