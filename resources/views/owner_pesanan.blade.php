<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Pesanan - Owner Dwiera Cake</title>

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
            padding: 18px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #d46a8c;
        }

        /* BARU: menu titik tiga */
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
            padding: 40px 8%;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .header h1 {
            color: #c9577c;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th {
            background: #fff0f4;
            color: #c9577c;
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #555;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* BARU: kolom catatan */
        .catatan-cell {
            max-width: 220px;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .dikonfirmasi {
            background: #d1ecf1;
            color: #0c5460;
        }

        .dibuat {
            background: #e2d9f3;
            color: #4b2e83;
        }

        .siap {
            background: #d4edda;
            color: #155724;
        }

        .selesai {
            background: #d4edda;
            color: #155724;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        @media (max-width: 600px) {
            nav {
                padding: 14px 5%;
            }

            .content {
                padding: 30px 5%;
            }

            .logo {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>
    @include('partials.mobile_bottom_nav')

    <nav>

        <div class="logo">
            Dwiera Cake
        </div>

        <!-- BARU: MENU TITIK TIGA -->
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
                <a href="{{ route('owner.pesanan') }}" class="active">Data Pesanan</a>
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

    </nav>

    <div class="content">

        <div class="header">

            <h1>
                Rekap Pesanan
            </h1>

            <p>
                Daftar seluruh pesanan kue ulang tahun yang masuk ke sistem.
            </p>

        </div>

        <div class="table-container">

            @if($pesanan->count() > 0)

                <table>

                    <thead>

                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Kue</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Pengambilan</th>
                            <th>Pembayaran</th>
                            <th>Catatan</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($pesanan as $item)

                            <tr>

                                <td>
                                    #{{ $item->id_pesanan }}
                                </td>

                                <td>
                                    {{ $item->user->nama_users ?? '-' }}
                                </td>

                                <td>

                                    @foreach($item->detailPesanans as $detail)

                                        {{ $detail->produk->nama_kue ?? '-' }}

                                        @if(!$loop->last)
                                            <br>
                                        @endif

                                    @endforeach

                                </td>

                                <td>

                                    @foreach($item->detailPesanans as $detail)

                                        {{ $detail->jumlah }}

                                        @if(!$loop->last)
                                            <br>
                                        @endif

                                    @endforeach

                                </td>

                                <td>
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td>

                                    {{ $item->tanggal_pengambilan
                                        ? \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d-m-Y')
                                        : '-'
                                    }}

                                    <br>

                                    <small>
                                        {{ $item->jadwalPengambilan->jam_pengambilan ?? '-' }}
                                    </small>

                                </td>

                                <td>
                                    {{ ucfirst($item->pembayaran->metode_pembayaran ?? '-') }}
                                </td>

                                <!-- BARU: catatan pesanan -->
                                <td class="catatan-cell">
                                    {{ !empty($item->catatan) ? $item->catatan : '-' }}
                                </td>

                                <td>

                                    @php
                                        $statusClass = match($item->status_pesanan) {
                                            'menunggu' => 'menunggu',
                                            'dikonfirmasi' => 'dikonfirmasi',
                                            'dibuat' => 'dibuat',
                                            'siap_diambil' => 'siap',
                                            'selesai' => 'selesai',
                                            default => 'menunggu',
                                        };

                                        $statusText = match($item->status_pesanan) {
                                            'menunggu' => 'Menunggu',
                                            'dikonfirmasi' => 'Dikonfirmasi',
                                            'dibuat' => 'Sedang Dibuat',
                                            'siap_diambil' => 'Siap Diambil',
                                            'selesai' => 'Selesai',
                                            default => ucfirst($item->status_pesanan),
                                        };
                                    @endphp

                                    <span class="status {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty">

                    Belum ada pesanan yang masuk.

                </div>

            @endif

        </div>

        <div style="text-align:center; margin-top:30px; padding-bottom:10px;">
            <a href="{{ route('owner') }}" style="display:inline-block; padding:11px 22px; background:#f8dce6; color:#a94566; text-decoration:none; border-radius:9px; font-size:13px; font-weight:bold;">
                ← Kembali ke Dashboard
            </a>
        </div>

    </div>

    <!-- BARU: SCRIPT MENU TITIK TIGA -->
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