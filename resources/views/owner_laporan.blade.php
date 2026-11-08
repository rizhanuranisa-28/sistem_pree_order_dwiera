<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laporan Penjualan - Dwiera Cake</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff6f8;
            color: #333;
        }

        nav {
            background: white;
            padding: 18px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .nav-logo {
            font-size: 25px;
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

        .dots-btn:hover {
            background: #f8dce6;
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
            z-index: 50;
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

        .content {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 45px 50px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #c9577c;
            font-size: 32px;
            margin-bottom: 8px;
        }

        .header p {
            color: #777;
            font-size: 16px;
        }

        /* BARU: filter periode + unduh PDF */
        .pdf-form {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .pdf-form label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #555;
        }

        .pdf-form input[type="date"] {
            padding: 10px;
            border: 1px solid #e8c5d2;
            border-radius: 8px;
            font-size: 14px;
        }

        .pdf-form button {
            padding: 11px 20px;
            border: none;
            border-radius: 9px;
            background: #d46a8c;
            color: white;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
        }

        .pdf-form button:hover {
            background: #c9577c;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            color: #666;
            font-size: 16px;
            margin-bottom: 14px;
        }

        .stat-card .number {
            color: #c9577c;
            font-size: 27px;
            font-weight: bold;
        }

        .table-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        .table-container h2 {
            color: #c9577c;
            margin-bottom: 25px;
            font-size: 23px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th {
            background: #f8dce6;
            color: #555;
            padding: 15px;
            text-align: left;
            font-size: 14px;
            white-space: nowrap;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #444;
        }

        tr:hover {
            background: #fff8fa;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .menunggu {
            background: #fff0c7;
            color: #9a7200;
        }

        .dikonfirmasi {
            background: #dceeff;
            color: #2773aa;
        }

        .dibuat {
            background: #eee0ff;
            color: #7540a5;
        }

        .siap {
            background: #dcf8e4;
            color: #278345;
        }

        .selesai {
            background: #d8f5ed;
            color: #18856a;
        }

        .empty-data {
            text-align: center;
            padding: 35px;
            color: #999;
        }

        @media (max-width: 900px) {

            nav {
                padding: 16px 25px;
            }

            .content {
                padding: 35px 25px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            nav {
                padding: 14px 18px;
            }

            .nav-logo {
                font-size: 21px;
            }

            .content {
                padding: 25px 18px;
            }

            .header h1 {
                font-size: 27px;
            }

            .header p {
                font-size: 14px;
                line-height: 1.5;
            }

            .pdf-form {
                flex-direction: column;
                align-items: stretch;
            }

            .pdf-form input[type="date"],
            .pdf-form button {
                width: 100%;
            }

            .stat-card {
                padding: 22px;
            }

            .stat-card .number {
                font-size: 24px;
            }

            .table-container {
                padding: 20px;
            }

            .table-container h2 {
                font-size: 20px;
            }

        }

    </style>

</head>

<body>

    <nav>

        <div class="nav-logo">
            Dwiera Cake
        </div>

        <div class="dots-wrap">

            <button
                type="button"
                class="dots-btn"
                id="dotsBtn"
                aria-label="Buka menu"
                aria-expanded="false"
            >
                ⋮
            </button>

            <div class="dots-menu" id="dotsMenu">

                <a href="{{ route('owner') }}">Dashboard</a>
                <a href="{{ route('owner.pesanan') }}">Data Pesanan</a>
                <a href="{{ route('owner.produk') }}">Data Produk</a>
                <a href="{{ route('owner.laporan') }}" class="active">Laporan Penjualan</a>
                <a href="{{ route('owner.admin') }}">Kelola Admin</a>

                <hr>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>

            </div>

        </div>

    </nav>

    <main class="content">

        <div class="header">

            <h1>
                Laporan Penjualan
            </h1>

            <p>
                Ringkasan penjualan dan transaksi Sistem Pre-Order Kue Ulang Tahun Dwiera.
            </p>

        </div>


        {{-- BARU: filter periode + unduh PDF --}}
        <form
            action="{{ route('owner.laporan.pdf') }}"
            method="GET"
            target="_blank"
            class="pdf-form"
        >

            <div>
                <label for="dari">Dari tanggal</label>
                <input
                    type="date"
                    id="dari"
                    name="dari"
                    value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                    required
                >
            </div>

            <div>
                <label for="sampai">Sampai tanggal</label>
                <input
                    type="date"
                    id="sampai"
                    name="sampai"
                    value="{{ now()->format('Y-m-d') }}"
                    required
                >
            </div>

            <button type="submit">
                📄 Unduh Laporan PDF
            </button>

        </form>


        <div class="stats">

            <div class="stat-card">

                <h3>
                    Total Penjualan
                </h3>

                <div class="number">

                    Rp {{ number_format(
                        $totalPenjualan,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

            </div>


            <div class="stat-card">

                <h3>
                    Jumlah Transaksi
                </h3>

                <div class="number">

                    {{ $jumlahTransaksi }}

                </div>

            </div>


            <div class="stat-card">

                <h3>
                    Rata-rata Transaksi
                </h3>

                <div class="number">

                    Rp {{ number_format(
                        $rataRata,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

            </div>

        </div>


        <div class="table-container">

            <h2>
                Detail Penjualan
            </h2>


            <table>

                <thead>

                    <tr>

                        <th>Tanggal</th>

                        <th>No. Pesanan</th>

                        <th>Pelanggan</th>

                        <th>Produk</th>

                        <th>Total</th>

                        <th>Pembayaran</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($pesanan as $item)

                        <tr>

                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $item->tanggal_pesan
                                )->format('d/m/Y') }}

                            </td>


                            <td>

                                #{{ $item->id_pesanan }}

                            </td>


                            <td>

                                {{ $item->user->nama_users ?? '-' }}

                            </td>


                            <td>

                                @forelse (
                                    $item->detailPesanans
                                    as $detail
                                )

                                    {{ $detail->produk->nama_kue ?? '-' }}

                                    @if (!$loop->last)
                                        ,
                                    @endif

                                @empty

                                    -

                                @endforelse

                            </td>


                            <td>

                                Rp {{ number_format(
                                    $item->total_harga,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            <td>

                                @if ($item->pembayaran)

                                    {{ ucfirst(
                                        $item->pembayaran
                                            ->metode_pembayaran
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @php

                                    $status =
                                        $item->status_pesanan;

                                    $statusText = [
                                        'menunggu' => 'Menunggu',
                                        'dikonfirmasi' => 'Dikonfirmasi',
                                        'dibuat' => 'Sedang Dibuat',
                                        'siap_diambil' => 'Siap Diambil',
                                        'selesai' => 'Selesai',
                                    ];

                                    $statusClass = [
                                        'menunggu' => 'menunggu',
                                        'dikonfirmasi' => 'dikonfirmasi',
                                        'dibuat' => 'dibuat',
                                        'siap_diambil' => 'siap',
                                        'selesai' => 'selesai',
                                    ];

                                @endphp


                                <span class="status {{ $statusClass[$status] ?? 'menunggu' }}">

                                    {{ $statusText[$status] ?? ucfirst($status) }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-data"
                            >

                                Belum ada data penjualan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

<div style="width:100%; text-align:center; margin-top:40px; padding:20px 0 30px;">
    <a href="{{ route('owner') }}" style="display:inline-block; padding:11px 22px; background:#f8dce6; color:#a94566; text-decoration:none; border-radius:9px; font-size:13px; font-weight:bold;">
        ← Kembali ke Dashboard
    </a>
</div>

    </main>

    <script>
        (function () {
            var tombol = document.getElementById('dotsBtn');
            var menu = document.getElementById('dotsMenu');

            function tutup() {
                menu.classList.remove('open');
                tombol.setAttribute('aria-expanded', 'false');
            }

            tombol.addEventListener('click', function (e) {
                e.stopPropagation();
                var buka = menu.classList.toggle('open');
                tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) { tutup(); }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { tutup(); }
            });
        })();
    </script>

</body>

</html>