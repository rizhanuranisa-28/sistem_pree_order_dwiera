<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Penjualan - Dwiera</title>

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

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 245px;
            height: 100vh;
            background: white;
            padding: 30px 20px;
            box-shadow: 3px 0 18px rgba(0,0,0,0.06);
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

        /* MAIN */
        .main {
            margin-left: 245px;
            padding: 35px 40px;
        }

        /* HEADER */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }

        /* PERIOD */
        .period-box {
            background: white;
            padding: 18px 20px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .period-title {
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .period-value {
            color: #c9577c;
            font-size: 15px;
            font-weight: bold;
        }

        /* SUMMARY */
        .summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 22px;
        }

        .summary-card {
            background: white;
            padding: 22px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .summary-card h3 {
            color: #888;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .summary-number {
            color: #c9577c;
            font-size: 24px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        /* PANEL DAN GRAFIK */
        .panel {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 22px;
        }

        .panel-title {
            color: #c9577c;
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 22px;
        }

        .chart-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .chart {
            min-width: 540px;
            height: 320px;
            position: relative;
        }

        .chart-grid {
            position: absolute;
            left: 55px;
            right: 15px;
            top: 20px;
            bottom: 55px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .grid-line {
            border-top: 1px dashed #eedee4;
            width: 100%;
        }

        .chart-label-y {
            position: absolute;
            left: 0;
            width: 45px;
            text-align: right;
            font-size: 11px;
            color: #999;
            transform: translateY(-50%);
        }

        .chart-svg {
            position: absolute;
            left: 55px;
            right: 15px;
            top: 20px;
            width: calc(100% - 70px);
            height: 245px;
            overflow: visible;
        }

        .chart-area {
            fill: url(#gradient);
        }

        .chart-line {
            fill: none;
            stroke: #d46a8c;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .point {
            fill: white;
            stroke: #d46a8c;
            stroke-width: 3;
        }

        .x-labels {
            position: absolute;
            left: 55px;
            right: 15px;
            bottom: 10px;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            text-align: center;
        }

        .x-labels span {
            color: #999;
            font-size: 11px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        /* PRODUCT ANALYSIS */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            min-width: 0;
        }

        .product-card h2 {
            color: #c9577c;
            font-size: 18px;
            margin-bottom: 18px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            text-align: left;
            color: #999;
            font-size: 11px;
            padding: 10px 6px;
            border-bottom: 1px solid #f0e3e7;
        }

        .product-table td {
            padding: 13px 6px;
            font-size: 12px;
            color: #555;
            border-bottom: 1px solid #f8edef;
        }

        .product-table tr:last-child td {
            border-bottom: none;
        }

        .rank {
            color: #c9577c;
            font-weight: bold;
        }

        .product-name {
            font-weight: bold;
            color: #555;
        }

        .money {
            color: #c9577c;
            font-weight: bold;
        }

        /* HIGHLIGHT */
        .highlight {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-top: 22px;
        }

        .highlight-card {
            padding: 20px;
            border-radius: 14px;
            background: #fff8fa;
            border: 1px solid #f3dfe6;
            min-width: 0;
        }

        .highlight-label {
            color: #999;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .highlight-name {
            color: #c9577c;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
            overflow-wrap: anywhere;
        }

        .highlight-info {
            color: #777;
            font-size: 12px;
            line-height: 1.6;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .main {
                padding: 25px;
            }

            .summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .logout {
                position: static;
                margin-top: 20px;
            }

            .main {
                margin-left: 0;
                padding: 20px 15px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .summary {
                grid-template-columns: 1fr;
            }

            .highlight {
                grid-template-columns: 1fr;
            }

            .period-box {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    @include('partials.mobile_bottom_nav')

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">Dwiera</div>

        <nav class="menu">
            <a href="{{ route('owner') }}">Dashboard</a>
            <a href="{{ route('owner.pesanan') }}">Rekap Pesanan</a>
            <a href="{{ route('owner.laporan') }}">Laporan Penjualan</a>
            <a href="{{ route('owner.grafik') }}" class="active">
                Grafik Penjualan
            </a>
        </nav>

        <div class="logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">

        <!-- HEADER -->
        <div class="topbar">
            <div class="title">
                <h1>Grafik Penjualan</h1>
                <p>Analisis penjualan dan performa produk Dwiera.</p>
            </div>

            <div class="owner-badge">Owner</div>
        </div>

        <!-- PERIODE -->
        <div class="period-box">
            <div>
                <div class="period-title">Kategori Grafik</div>
                <div class="period-value">5 Produk Terlaris</div>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-card">
                <h3>Total Pendapatan</h3>
                <div class="summary-number">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
            </div>

            <div class="summary-card">
                <h3>Total Pesanan</h3>
                <div class="summary-number">{{ $totalPesanan }}</div>
            </div>

            <div class="summary-card">
                <h3>Produk Terjual</h3>
                <div class="summary-number">{{ $produkTerjual }}</div>
            </div>
        </div>

        <!-- GRAPH -->
        <div class="panel">
            <div class="panel-title">5 Produk Kue Terlaris</div>

            @php
                $namaProduk = $grafikNamaProduk->toArray();
                $jumlahTerjual = $grafikJumlahTerjual->toArray();

                $maxGrafik = count($jumlahTerjual) > 0
                    ? max($jumlahTerjual)
                    : 0;

                $maxGrafik = $maxGrafik > 0 ? $maxGrafik : 1;
                $jumlahData = count($jumlahTerjual);
                $points = [];

                foreach ($jumlahTerjual as $index => $nilai) {
                    $x = $jumlahData <= 1
                        ? 50
                        : 5 + ($index / ($jumlahData - 1)) * 90;

                    $y = 100 - (($nilai / $maxGrafik) * 90);

                    $points[] = [
                        'x' => $x,
                        'y' => $y,
                        'nilai' => $nilai,
                        'nama' => $namaProduk[$index] ?? 'Produk',
                    ];
                }

                $linePoints = '';

                foreach ($points as $point) {
                    $linePoints .= $point['x'] . ',' . $point['y'] . ' ';
                }

                $areaPoints = count($points) > 0
                    ? '0,100 ' . trim($linePoints) . ' 100,100'
                    : '';
            @endphp

            <div class="chart-wrapper">
                <div class="chart">

                    <div class="chart-grid">
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                        <div class="grid-line"></div>
                    </div>

                    {{-- Angka jumlah produk terjual di sumbu kiri --}}
                    @php
                        $labelGrafik = [
                            $maxGrafik,
                            round($maxGrafik * 0.75),
                            round($maxGrafik * 0.50),
                            round($maxGrafik * 0.25),
                            0,
                        ];
                    @endphp

                    @foreach ($labelGrafik as $index => $label)
                        <span
                            class="chart-label-y"
                            style="top: {{ 20 + ($index * 61.25) }}px;"
                        >
                            {{ $label }}
                        </span>
                    @endforeach

                    <svg
                        class="chart-svg"
                        viewBox="0 0 100 100"
                        preserveAspectRatio="none"
                    >
                        <defs>
                            <linearGradient
                                id="gradient"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1"
                            >
                                <stop
                                    offset="0%"
                                    stop-color="#d46a8c"
                                    stop-opacity="0.35"
                                />
                                <stop
                                    offset="100%"
                                    stop-color="#f8dce6"
                                    stop-opacity="0.05"
                                />
                            </linearGradient>
                        </defs>

                        @if (count($points) > 0)
                            <polygon
                                class="chart-area"
                                points="{{ $areaPoints }}"
                            />

                            <polyline
                                class="chart-line"
                                points="{{ trim($linePoints) }}"
                                vector-effect="non-scaling-stroke"
                            />

                            @foreach ($points as $point)
                                <circle
                                    class="point"
                                    cx="{{ $point['x'] }}"
                                    cy="{{ $point['y'] }}"
                                    r="1.8"
                                    vector-effect="non-scaling-stroke"
                                >
                                    <title>
                                        {{ $point['nama'] }}:
                                        {{ $point['nilai'] }} kue terjual
                                    </title>
                                </circle>
                            @endforeach
                        @endif
                    </svg>

                    {{-- Nama produk hanya ditampilkan di bawah grafik --}}
                    <div class="x-labels">
                        @forelse ($points as $point)
                            <span>{{ $point['nama'] }}</span>
                        @empty
                            <span>Belum ada data penjualan</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT ANALYSIS -->
        <div class="product-grid">

            <!-- PRODUK TERLARIS -->
            <div class="product-card">
                <h2>Produk Terlaris</h2>

                <div class="table-wrapper">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Produk</th>
                                <th>Terjual</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($topProduk as $index => $produk)
                                <tr>
                                    <td class="rank">{{ $index + 1 }}</td>
                                    <td class="product-name">{{ $produk->nama_kue }}</td>
                                    <td>{{ $produk->jumlah_terjual }}</td>
                                    <td class="money">
                                        Rp {{ number_format($produk->pendapatan_produk, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Belum ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PRODUK KURANG LAKU -->
            <div class="product-card">
                <h2>Produk Kurang Terjual</h2>

                <div class="table-wrapper">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Produk</th>
                                <th>Terjual</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bottomProduk as $index => $produk)
                                <tr>
                                    <td class="rank">{{ $index + 1 }}</td>
                                    <td class="product-name">{{ $produk->nama_kue }}</td>
                                    <td>{{ $produk->jumlah_terjual }}</td>
                                    <td class="money">
                                        Rp {{ number_format($produk->pendapatan_produk, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Belum ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- HIGHLIGHT -->
        <div class="highlight">

            <div class="highlight-card">
                <div class="highlight-label">Produk Paling Laris</div>

                @if ($produkPalingLaris)
                    <div class="highlight-name">
                        {{ $produkPalingLaris->nama_kue }}
                    </div>

                    <div class="highlight-info">
                        {{ $produkPalingLaris->jumlah_terjual }} produk terjual
                        • Rp {{ number_format($produkPalingLaris->pendapatan_produk, 0, ',', '.') }}
                    </div>
                @else
                    <div class="highlight-info">Belum ada data penjualan.</div>
                @endif
            </div>

            <div class="highlight-card">
                <div class="highlight-label">Produk Paling Sedikit Terjual</div>

                @if ($produkPalingSedikit)
                    <div class="highlight-name">
                        {{ $produkPalingSedikit->nama_kue }}
                    </div>

                    <div class="highlight-info">
                        {{ $produkPalingSedikit->jumlah_terjual }} produk terjual
                        • Rp {{ number_format($produkPalingSedikit->pendapatan_produk, 0, ',', '.') }}
                    </div>
                @else
                    <div class="highlight-info">Belum ada data penjualan.</div>
                @endif
            </div>

        </div>

    </main>
</body>
</html>