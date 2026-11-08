<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Dwiera</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff7f9;
            color: #444;
        }

        nav {
            background: white;
            padding: 18px 30px;
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

        .page {
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #c9577c;
            margin-bottom: 10px;
        }

        .description {
            color: #888;
            margin-bottom: 25px;
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #c9577c;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #f3e5ea;
            vertical-align: top;
        }

        th {
            color: #c9577c;
            background: #fff0f4;
        }

        .nama-kue {
            font-weight: bold;
            color: #444;
        }

        .nama-ukuran {
            color: #666;
        }

        .harga-ukuran {
            color: #c9577c;
            font-weight: bold;
            white-space: nowrap;
        }

        .empty {
            text-align: center;
            color: #888;
            padding: 25px;
        }

        .belum-ada-ukuran {
            color: #aaa;
            font-size: 13px;
            font-style: italic;
        }

        @media (max-width: 600px) {
            nav {
                padding: 14px 15px;
            }

            .nav-logo {
                font-size: 21px;
            }

            .page {
                padding: 15px;
            }

            .panel {
                padding: 15px;
            }

            th,
            td {
                padding: 11px 9px;
                font-size: 13px;
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
                <a href="{{ route('owner.produk') }}" class="active">Data Produk</a>
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

    <div class="page">

        <main class="container">

            <a href="{{ route('owner') }}" class="back">
                &larr; Kembali ke Dashboard
            </a>

            <h1>Data Produk Kue Dwiera</h1>

            <p class="description">
                Daftar produk kue beserta ukuran dan harga setiap produk.
            </p>

            <section class="panel">

                <h2>Daftar Produk</h2>

                <table>

                    <thead>
                        <tr>
                            <th style="width: 8%;">No.</th>
                            <th style="width: 30%;">Nama Kue</th>
                            <th style="width: 25%;">Ukuran</th>
                            <th style="width: 37%;">Harga</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($produk as $item)

                            @if ($item->ukuranProduks->count() > 0)

                                @foreach ($item->ukuranProduks as $ukuran)

                                    <tr>

                                        <td>
                                            @if ($loop->first)
                                                {{ $loop->parent->iteration }}
                                            @endif
                                        </td>

                                        <td>
                                            @if ($loop->first)
                                                <strong class="nama-kue">
                                                    {{ $item->nama_kue }}
                                                </strong>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="nama-ukuran">
                                                {{ $ukuran->nama_ukuran }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="harga-ukuran">
                                                Rp {{ number_format($ukuran->harga, 0, ',', '.') }}
                                            </span>
                                        </td>

                                    </tr>

                                @endforeach

                            @else

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong class="nama-kue">
                                            {{ $item->nama_kue }}
                                        </strong>
                                    </td>

                                    <td colspan="2">
                                        <span class="belum-ada-ukuran">
                                            Belum ada data ukuran.
                                        </span>
                                    </td>
                                </tr>

                            @endif

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    Belum ada data produk.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </section>

        </main>

    </div>

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

                tombol.setAttribute(
                    'aria-expanded',
                    buka ? 'true' : 'false'
                );
            });

            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) {
                    tutup();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    tutup();
                }
            });
        })();
    </script>

</body>
</html>