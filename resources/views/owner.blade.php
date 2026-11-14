<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - Dwiera</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff7f9;
            color: #333;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 245px;
            height: 100vh;
            background: #fff;
            padding: 30px 20px;
            box-shadow: 3px 0 18px rgba(0, 0, 0, 0.06);
            z-index: 10;
        }

        .logo {
            text-align: center;
            color: #d46a8c;
            font-size: 27px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #fff0f4;
            color: #d46a8c;
        }

        .menu a.active {
            background: #f8dce6;
            color: #c9577c;
            font-weight: bold;
        }

        .logout {
            position: absolute;
            bottom: 25px;
            left: 20px;
            right: 20px;
        }

        .logout button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 9px;
            background: #d46a8c;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .logout button:hover {
            background: #c9577c;
        }

        .main {
            margin-left: 245px;
            padding: 35px 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .title h1 {
            color: #c9577c;
            font-size: 28px;
            margin-bottom: 7px;
        }

        .title p {
            color: #888;
            font-size: 14px;
        }

        .owner-badge {
            background: white;
            padding: 10px 18px;
            border-radius: 25px;
            color: #c9577c;
            font-weight: bold;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            min-width: 0;
        }

        .card-title {
            color: #888;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .card-number {
            color: #c9577c;
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .card-info {
            color: #aaa;
            font-size: 12px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
            gap: 22px;
            align-items: start;
        }

        .panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            min-width: 0;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .panel-header h2 {
            color: #c9577c;
            font-size: 19px;
        }

        .period {
            padding: 8px 12px;
            border: 1px solid #eee;
            border-radius: 8px;
            color: #777;
            background: #fff;
            font-size: 12px;
        }

        /* Grafik penjualan terlaris */
        .chart-wrapper {
            width: 100%;
            padding-top: 10px;
            overflow-x: auto;
        }

        .chart-empty {
            padding: 45px 10px;
            text-align: center;
            color: #999;
            background: #fff9fb;
            border-radius: 10px;
            font-size: 14px;
        }

        /* Layout: daftar nama kue (legend) di pinggir kiri + grafik garis di kanan */
        .kue-chart-layout {
            display: flex;
            align-items: flex-start;
            gap: 22px;
            flex-wrap: wrap;
        }

        .kue-legend {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 150px;
            max-width: 190px;
            padding-top: 20px;
        }

        .kue-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kue-legend-swatch {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .kue-legend-name {
            flex: 1;
            min-width: 0;
            color: #555;
            font-size: 12px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .kue-legend-value {
            color: #999;
            font-size: 11px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .kue-chart-area {
            flex: 1;
            min-width: 420px;
        }

        .kue-chart {
            min-width: 480px;
            height: 320px;
            position: relative;
        }

        .kue-grid {
            position: absolute;
            left: 50px;
            right: 15px;
            top: 20px;
            bottom: 55px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kue-grid-line {
            border-top: 1px dashed #eedee4;
            width: 100%;
        }

        .kue-y-label {
            position: absolute;
            left: 0;
            width: 42px;
            text-align: right;
            font-size: 11px;
            color: #999;
            transform: translateY(-50%);
        }

        .kue-svg {
            position: absolute;
            left: 50px;
            right: 15px;
            top: 20px;
            width: calc(100% - 65px);
            height: 245px;
            overflow: visible;
        }

        .kue-line {
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .kue-point {
            fill: white;
            stroke-width: 2.5;
        }

        .kue-x-labels {
            position: absolute;
            left: 50px;
            right: 15px;
            bottom: 10px;
            display: grid;
            gap: 4px;
            text-align: center;
        }

        .kue-x-labels span {
            color: #999;
            font-size: 10px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        /* Pesanan terbaru */
        .orders {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .order {
            padding: 15px;
            border: 1px solid #f4e8ec;
            border-radius: 10px;
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .order-id {
            color: #c9577c;
            font-weight: bold;
            font-size: 13px;
        }

        .order-date {
            color: #999;
            font-size: 11px;
        }

        .order-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .status {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 11px;
            background: #f3f3f3;
            color: #777;
        }

        .status.menunggu {
            background: #fff0d6;
            color: #a86a00;
        }

        .status.dikonfirmasi {
            background: #e5efff;
            color: #3566b7;
        }

        .status.dibuat {
            background: #f0e7ff;
            color: #8052b5;
        }

        .status.siap {
            background: #e1f5e8;
            color: #27804a;
        }

        .status.selesai {
            background: #dff5e8;
            color: #207544;
        }
        /* BARU: menu titik tiga (HP) */
        .mobile-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            padding: 0 16px;
            background: #fff;
            border-bottom: 1px solid #f1dfe5;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .mobile-logo {
            font-size: 24px;
            font-weight: bold;
            color: #d46a8c;
        }

        .dots-wrap {
            position: relative;
        }

        .dots-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            background: #fff0f4;
            color: #c9577c;
            font-size: 24px;
            font-weight: bold;
            line-height: 1;
            cursor: pointer;
        }

        .dots-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            width: 220px;
            padding: 8px;
            background: #fff;
            border: 1px solid #f1dfe5;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .dots-menu.open {
            display: block;
        }

        .dots-menu a,
        .dots-menu button {
            width: 100%;
            display: block;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .dots-menu a:hover,
        .dots-menu button:hover {
            background: #fff0f4;
            color: #d46a8c;
        }

        .dots-menu a.active {
            background: #f8dce6;
            color: #c9577c;
            font-weight: bold;
        }

        .dots-menu hr {
            border: none;
            border-top: 1px solid #f4e8ec;
            margin: 6px 0;
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .mobile-bar { display: flex; }
            .main { margin-left: 0; padding: 80px 15px 22px; }
        }

        @media (max-width: 1100px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 700px) {
            
            .main {
                margin-left: 0;
                padding: 22px 15px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .cards {
                grid-template-columns: minmax(0, 1fr);
            }

            .panel {
                padding: 18px;
            }

            .panel-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .kue-legend {
                flex-direction: row;
                flex-wrap: wrap;
                max-width: 100%;
                padding-top: 0;
                margin-bottom: 10px;
            }

            .kue-legend-item {
                min-width: 45%;
            }

            .kue-chart {
                min-width: 560px;
            }
        }
    </style>
</head>

<body>
@include('partials.mobile_bottom_nav')

        <!-- BARU: BAR ATAS + MENU TITIK TIGA (HP) -->
    <div class="mobile-bar">
        <div class="mobile-logo">Dwiera</div>

        <div class="dots-wrap">
            <button type="button" class="dots-btn" id="dotsBtn"
                    aria-label="Buka menu" aria-expanded="false">⋮</button>

            <div class="dots-menu" id="dotsMenu">
                <a href="{{ route('owner') }}" class="active">Dashboard</a>
                <a href="{{ route('owner.pesanan') }}">Data Pesanan</a>
                <a href="{{ route('owner.produk') }}">Data Produk</a>
                <a href="{{ route('owner.laporan') }}">Laporan Penjualan</a>
                <a href="{{ route('owner.admin') }}">Kelola Admin</a>
                <hr>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="logo">Dwiera</div>

        <nav class="menu">
            <a href="{{ route('owner') }}" class="active">
                Dashboard
            </a>

            <a href="{{ route('owner.pesanan') }}">
                Data Pesanan
            </a>

            <a href="{{ route('owner.produk') }}">
                Data Produk
            </a>

            <a href="{{ route('owner.laporan') }}">
                Laporan Penjualan
            </a>

            <a href="{{ route('owner.admin') }}">
                Kelola Admin
            </a>
        </nav>

        <form class="logout" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </aside>

    <main class="main">

        <header class="topbar">
            <div class="title">
                <h1>Dashboard Owner</h1>
                <p>Ringkasan penjualan dan pesanan kue Dwiera</p>
            </div>

            <div class="owner-badge">Owner</div>
        </header>

        <!-- Kartu statistik -->
        <section class="cards">

            <div class="card">
                <div class="card-title">Total Penjualan</div>

                <div class="card-number">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </div>

                <div class="card-info">Total pembayaran tercatat</div>
            </div>

            <div class="card">
                <div class="card-title">Total Pesanan</div>
                <div class="card-number">{{ $totalPesanan }}</div>
                <div class="card-info">Seluruh pesanan</div>
            </div>

            <div class="card">
                <div class="card-title">Pesanan Selesai</div>
                <div class="card-number">{{ $pesananSelesai }}</div>
                <div class="card-info">Pesanan telah selesai</div>
            </div>

            <div class="card">
                <div class="card-title">Pesanan Berjalan</div>
                <div class="card-number">{{ $pesananPending }}</div>
                <div class="card-info">Menunggu atau diproses</div>
            </div>

        </section>

        <section class="dashboard-grid">

            <!-- Grafik penjualan terlaris -->
            <div class="panel">

                <div class="panel-header">
                    <h2>Penjualan Terlaris</h2>
                    <div class="period">Penjualan per bulan tahun ini</div>
                </div>

                @php
                    // Nama produk datang dari controller sudah urut dari PALING LARIS
                    // ke paling tidak laris (orderByDesc di OwnerDashboardController).
                    $namaProduk = $grafikNamaProduk->toArray();
                    $dataBulanan = $grafikDataBulanan->toArray();
                    $jumlahBulan = count($dataBulanan);

                    // Total penjualan per kue (dipakai untuk urutan legend & label nilai)
                    $totalPerKue = [];
                    foreach ($namaProduk as $nama) {
                        $total = 0;
                        foreach ($dataBulanan as $baris) {
                            $total += (int) ($baris[$nama] ?? 0);
                        }
                        $totalPerKue[$nama] = $total;
                    }

                    // Urutkan legend dari PALING RENDAH ke PALING TINGGI sesuai permintaan,
                    // tampil di pinggir (kiri) grafik dari atas ke bawah.
                    $namaUrutRendahKeTinggi = $namaProduk;
                    usort($namaUrutRendahKeTinggi, function ($a, $b) use ($totalPerKue) {
                        return $totalPerKue[$a] <=> $totalPerKue[$b];
                    });

                    // Warna tiap kue mengikuti entitasnya (nama kue), bukan urutan rangking,
                    // supaya warna tidak tertukar saat urutan legend berubah.
                    $paletKue = ['#d46a8c', '#5b7fd4', '#c7821f', '#2e9c66', '#8a5fd1'];
                    $warnaPerKue = [];
                    foreach ($namaProduk as $i => $nama) {
                        $warnaPerKue[$nama] = $paletKue[$i % count($paletKue)];
                    }

                    // Nilai maksimum dari seluruh data (dipakai untuk skala sumbu)
                    $nilaiMaksimum = 0;
                    foreach ($dataBulanan as $baris) {
                        foreach ($namaProduk as $nama) {
                            $nilaiMaksimum = max($nilaiMaksimum, (int) ($baris[$nama] ?? 0));
                        }
                    }
                    $nilaiMaksimum = max($nilaiMaksimum, 1);

                    // Cari kelipatan angka yang "rapi" (1, 2, 5, 10, 20, 25, 50, ...)
                    // supaya label sumbu kiri tidak dobel/duplikat walau datanya kecil.
                    $kandidatLangkah = [1, 2, 5, 10, 20, 25, 50, 100, 200, 250, 500, 1000];
                    $langkahSumbu = null;
                    foreach ($kandidatLangkah as $kandidat) {
                        if ($kandidat * 4 >= $nilaiMaksimum) {
                            $langkahSumbu = $kandidat;
                            break;
                        }
                    }
                    if ($langkahSumbu === null) {
                        $pangkat = pow(10, floor(log10($nilaiMaksimum)));
                        $langkahSumbu = (int) (ceil($nilaiMaksimum / 4 / $pangkat) * $pangkat);
                    }
                    $skalaMaksimum = $langkahSumbu * 4;

                    $labelSumbuY = [
                        $skalaMaksimum,
                        $skalaMaksimum - $langkahSumbu,
                        $skalaMaksimum - (2 * $langkahSumbu),
                        $skalaMaksimum - (3 * $langkahSumbu),
                        0,
                    ];

                    // Titik-titik garis per kue (sumbu bawah tetap bulan).
                    // Saat nilainya 0, garis digeser sedikit per kue (bertingkat tipis)
                    // supaya beberapa garis yang sama-sama 0 tidak numpuk jadi satu garis.
                    $garisPerKue = [];
                    foreach ($namaUrutRendahKeTinggi as $indexSeri => $nama) {
                        $titik = [];
                        foreach ($dataBulanan as $index => $baris) {
                            $x = $jumlahBulan <= 1
                                ? 50
                                : ($index / ($jumlahBulan - 1)) * 100;

                            $nilai = (int) ($baris[$nama] ?? 0);

                            if ($nilai > 0) {
                                $y = 100 - (($nilai / $skalaMaksimum) * 90);
                            } else {
                                // geser tipis berdasarkan urutan kue supaya baris 0 tidak bertumpuk
                                $y = 100 - ($indexSeri * 0.9);
                            }

                            $titik[] = [
                                'x' => $x,
                                'y' => $y,
                                'nilai' => $nilai,
                                'bulan' => $baris['bulan'],
                            ];
                        }
                        $garisPerKue[$nama] = $titik;
                    }
                @endphp

                @if (count($namaProduk) > 0 && count($dataBulanan) > 0)

                    <div class="kue-chart-layout">

                        {{-- Nama kue terlaris di pinggir, urut dari paling rendah ke paling tinggi --}}
                        <div class="kue-legend">
                            @foreach ($namaUrutRendahKeTinggi as $nama)
                                <div class="kue-legend-item">
                                    <span
                                        class="kue-legend-swatch"
                                        style="background: {{ $warnaPerKue[$nama] }};"
                                    ></span>

                                    <span class="kue-legend-name" title="{{ $nama }}">
                                        {{ $nama }}
                                    </span>

                                   
                                </div>
                            @endforeach
                        </div>

                        <div class="kue-chart-area">
                            <div class="chart-wrapper">
                                <div class="kue-chart">

                                    <div class="kue-grid">
                                        <div class="kue-grid-line"></div>
                                        <div class="kue-grid-line"></div>
                                        <div class="kue-grid-line"></div>
                                        <div class="kue-grid-line"></div>
                                        <div class="kue-grid-line"></div>
                                    </div>

                                    @foreach ($labelSumbuY as $index => $label)
                                        <span
                                            class="kue-y-label"
                                            style="top: {{ 20 + ($index * 61.25) }}px;"
                                        >
                                            {{ $label }}
                                        </span>
                                    @endforeach

                                    <svg
                                        class="kue-svg"
                                        viewBox="0 0 100 100"
                                        preserveAspectRatio="none"
                                    >
                                        {{-- Garis digambar dari yang paling rendah ke paling tinggi
                                             supaya kue terlaris tampil paling atas/menonjol --}}
                                        @foreach ($namaUrutRendahKeTinggi as $nama)
                                            <polyline
                                                class="kue-line"
                                                style="stroke: {{ $warnaPerKue[$nama] }};"
                                                points="{{ collect($garisPerKue[$nama])->map(fn($t) => $t['x'] . ',' . $t['y'])->implode(' ') }}"
                                                vector-effect="non-scaling-stroke"
                                            />
                                        @endforeach

                                        @foreach ($namaUrutRendahKeTinggi as $nama)
                                            @foreach ($garisPerKue[$nama] as $titik)
                                                <circle
                                                    class="kue-point"
                                                    style="stroke: {{ $warnaPerKue[$nama] }};"
                                                    cx="{{ $titik['x'] }}"
                                                    cy="{{ $titik['y'] }}"
                                                    r="1.6"
                                                    vector-effect="non-scaling-stroke"
                                                >
                                                    <title>
                                                        {{ $nama }} — {{ $titik['bulan'] }}:
                                                        {{ $titik['nilai'] }} kue terjual
                                                    </title>
                                                </circle>
                                            @endforeach
                                        @endforeach
                                    </svg>

                                    {{-- Sumbu bawah tetap bulan --}}
                                    <div
                                        class="kue-x-labels"
                                        style="grid-template-columns: repeat({{ $jumlahBulan }}, minmax(0, 1fr));"
                                    >
                                        @foreach ($dataBulanan as $baris)
                                            <span>{{ $baris['bulan'] }}</span>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                @else

                    <div class="chart-empty">
                        Belum ada data penjualan kue yang selesai.
                    </div>

                @endif

            </div>

            <!-- Pesanan terbaru -->
            <div class="panel">

                <div class="panel-header">
                    <h2>Pesanan Terbaru</h2>
                </div>

                <div class="orders">

                    @forelse ($pesananTerbaru as $item)

                        <div class="order">

                            <div class="order-top">

                                <span class="order-id">
                                    #DW{{ str_pad($item->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="order-date">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pesan)->format('d/m/Y') }}
                                </span>

                            </div>

                            <div class="order-name">
                                {{ $item->user->nama_users ?? '-' }}
                            </div>

                            <span class="status
                                @if ($item->status_pesanan == 'menunggu') menunggu
                                @elseif ($item->status_pesanan == 'dikonfirmasi') dikonfirmasi
                                @elseif ($item->status_pesanan == 'dibuat') dibuat
                                @elseif ($item->status_pesanan == 'siap_diambil') siap
                                @elseif ($item->status_pesanan == 'selesai') selesai
                                @endif
                            ">

                                @if ($item->status_pesanan == 'menunggu')
                                    Menunggu Konfirmasi
                                @elseif ($item->status_pesanan == 'dikonfirmasi')
                                    Dikonfirmasi
                                @elseif ($item->status_pesanan == 'dibuat')
                                    Sedang Dibuat
                                @elseif ($item->status_pesanan == 'siap_diambil')
                                    Siap Diambil
                                @elseif ($item->status_pesanan == 'selesai')
                                    Selesai
                                @else
                                    {{ ucfirst($item->status_pesanan) }}
                                @endif

                            </span>

                        </div>

                    @empty

                        <div style="text-align:center; color:#999; padding:25px;">
                            Belum ada pesanan.
                        </div>

                    @endforelse

                </div>

            </div>

        </section>

    </main>
        <script>
        (function () {
            var tombol = document.getElementById('dotsBtn');
            var menu = document.getElementById('dotsMenu');
            function tutup() { menu.classList.remove('open'); tombol.setAttribute('aria-expanded', 'false'); }
            tombol.addEventListener('click', function (e) {
                e.stopPropagation();
                var buka = menu.classList.toggle('open');
                tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
            });
            document.addEventListener('click', function (e) { if (!menu.contains(e.target)) { tutup(); } });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { tutup(); } });
        })();
    </script>

</body>
</html>