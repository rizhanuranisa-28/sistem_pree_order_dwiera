<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Pesanan - Dwiera</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff5f8;
            color: #333;
        }

        .navbar {
            background: #e9a0b5;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            color: white;
        }

        .navbar > a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .dots-wrap {
            display: none;
            position: relative;
        }

        .dots-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-size: 24px;
            font-weight: bold;
            line-height: 1;
            cursor: pointer;
        }

        .dots-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .dots-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            width: 210px;
            padding: 8px;
            background: #fff;
            border: 1px solid #f0dfe5;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 50;
        }

        .dots-menu.open {
            display: block;
        }

        .dots-menu a,
        .dots-menu button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            text-decoration: none;
            color: #777;
            font-size: 14px;
            font-weight: normal;
            text-align: left;
            cursor: pointer;
        }

        .dots-menu a:hover,
        .dots-menu button:hover {
            background: #fff0f5;
            color: #d66f8d;
        }

        .dots-menu a.active {
            background: #f5b4c7;
            color: white;
            font-weight: bold;
        }

        .dots-menu hr {
            border: none;
            border-top: 1px solid #f4e8ec;
            margin: 6px 0;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            color: #d66b8c;
            margin-bottom: 8px;
        }

        .header p {
            color: #777;
        }

        .success {
            background: #dff5e4;
            color: #287a3d;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #ffe1e1;
            color: #a52a2a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-wrapper {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1250px;
        }

        th {
            background: #e9a0b5;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:hover {
            background: #fff8fa;
        }

        .catatan-cell {
            max-width: 220px;
            font-size: 13px;
            color: #555;
            line-height: 1.5;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .produk-detail {
            line-height: 1.6;
            min-width: 170px;
        }

        .produk-detail strong {
            color: #d66b8c;
        }

        .produk-detail small {
            color: #666;
            font-size: 12px;
        }

        .status-form {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 150px;
        }

        .status-select {
            padding: 8px 10px;
            border: 1px solid #e9a0b5;
            border-radius: 7px;
            background: white;
            color: #555;
            outline: none;
        }

        .status-select:focus {
            border-color: #d66b8c;
        }

        .save-btn {
            padding: 7px 10px;
            border: none;
            border-radius: 7px;
            background: #e9a0b5;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #d987a0;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #888;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #e9a0b5;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .back-btn:hover {
            background: #d987a0;
        }

        .add-btn {
            display: inline-block;
            background: #d94683;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin: 10px 0 20px;
        }

        .add-btn:hover {
            background: #c73572;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 14px 16px;
            }

            .navbar > a {
                display: none;
            }

            .dots-wrap {
                display: block;
            }

            .container {
                margin: 25px auto;
                padding: 0 14px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>Dwiera Cake</h2>

        <a href="{{ route('admin') }}">
            ← Kembali ke Dashboard
        </a>

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

                <a href="{{ route('admin') }}">
                    <span class="menu-icon">⌂</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.pesanan') }}" class="active">
                    <span class="menu-icon">🛍</span>
                    <span>Pesanan</span>
                </a>

                <a href="{{ route('admin.produk') }}">
                    <span class="menu-icon">🎂</span>
                    <span>Produk</span>
                </a>

                <a href="{{ route('admin.pembayaran') }}">
                    <span class="menu-icon">💳</span>
                    <span>Pembayaran</span>
                </a>

                <hr>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit">
                        <span class="menu-icon">↪</span>
                        <span>Logout</span>
                    </button>
                </form>

            </div>
        </div>
    </div>

    <div class="container">

        <div class="header">
            <h1>Kelola Pesanan</h1>

            <a href="{{ route('admin.pesanan.create') }}" class="add-btn">
                + Tambah Pesanan
            </a>

            <p>Daftar seluruh pesanan pelanggan Dwiera.</p>
        </div>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-wrapper">

            @if($pesanan->count() > 0)

                <table>

                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pesan</th>
                            <th>Pengambilan</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
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

                                <td class="produk-detail">
                                    @foreach($item->detailPesanans as $detail)

                                        <strong>
                                            {{ $detail->produk->nama_kue ?? '-' }}
                                        </strong>
                                        <br>

                                        <small>
                                            Ukuran: {{ $detail->ukuran ?? '-' }}
                                        </small>
                                        <br>

                                        <small>
                                            Harga: Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </small>

                                        @if(!$loop->last)
                                            <hr style="border: 0; border-top: 1px solid #eee; margin: 8px 0;">
                                        @endif

                                    @endforeach
                                </td>

                                <td>
                                    @foreach($item->detailPesanans as $detail)

                                        {{ $detail->jumlah }}

                                        @if(!$loop->last)
                                            <br><br>
                                        @endif

                                    @endforeach
                                </td>

                                <td>
                                    {{ $item->tanggal_pesan }}
                                </td>

                                <td>
                                    @if($item->jadwalPengambilan)
                                        {{ \Carbon\Carbon::parse($item->jadwalPengambilan->tanggal_pengambilan)->format('d-m-Y') }}
                                        <br>
                                        {{ substr((string) $item->jadwalPengambilan->jam_pengambilan, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ ucfirst($item->pembayaran->metode_pembayaran ?? '-') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td class="catatan-cell">
                                    {{ !empty($item->catatan) ? $item->catatan : '-' }}
                                </td>

                                <td>

                                    <form
                                        action="{{ route('admin.pesanan.status', $item->id_pesanan) }}"
                                        method="POST"
                                        class="status-form"
                                    >

                                        @csrf

                                        <select
                                            name="status_pesanan"
                                            class="status-select"
                                        >
                                            <option value="menunggu"
                                                {{ $item->status_pesanan == 'menunggu' ? 'selected' : '' }}>
                                                Menunggu Konfirmasi
                                            </option>

                                            <option value="dikonfirmasi"
                                                {{ $item->status_pesanan == 'dikonfirmasi' ? 'selected' : '' }}>
                                                Dikonfirmasi
                                            </option>

                                            <option value="dibuat"
                                                {{ $item->status_pesanan == 'dibuat' ? 'selected' : '' }}>
                                                Sedang Dibuat
                                            </option>

                                            <option value="siap_diambil"
                                                {{ $item->status_pesanan == 'siap_diambil' ? 'selected' : '' }}>
                                                Siap Diambil
                                            </option>

                                            <option value="selesai"
                                                {{ $item->status_pesanan == 'selesai' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                        </select>

                                        <button type="submit" class="save-btn">
                                            Simpan
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty">
                    Belum ada pesanan.
                </div>

            @endif

        </div>

        <a href="{{ route('admin') }}" class="back-btn">
            ← Kembali ke Dashboard
        </a>

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
                tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
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