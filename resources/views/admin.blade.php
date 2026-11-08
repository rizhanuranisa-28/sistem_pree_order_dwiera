<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

```
<title>Dashboard Admin - Dwiera</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background: #fff7fa;
        color: #333;
    }

    .layout {
        display: flex;
        min-height: 100vh;
    }

    /* ================= SIDEBAR ================= */

    .sidebar {
        width: 235px;
        background: #fff;
        border-right: 1px solid #f0dfe5;
        padding: 25px 18px;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
    }

    .admin-label {
        text-align: center;
        font-size: 11px;
        color: #c66b88;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 25px;
        letter-spacing: 1px;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .menu a {
        text-decoration: none;
        color: #777;
        padding: 13px 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        transition: 0.2s;
    }

    .menu a:hover {
        background: #fff0f5;
        color: #d66f8d;
    }

    .menu a.active {
        background: #f5b4c7;
        color: white;
        font-weight: bold;
    }

    .menu-icon {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .logout {
        position: absolute;
        bottom: 25px;
        left: 18px;
        right: 18px;
    }

    .logout button {
        width: 100%;
        border: none;
        background: transparent;
        color: #777;
        padding: 13px 15px;
        text-align: left;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .logout button:hover {
        background: #fff0f5;
        color: #d66f8d;
    }

    /* ================= MOBILE BAR ================= */

    .mobile-bar {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
        padding: 0 16px;
        background: #fff;
        border-bottom: 1px solid #f0dfe5;
        align-items: center;
        justify-content: space-between;
        z-index: 50;
    }

    .mobile-bar img {
        height: 38px;
        width: auto;
        object-fit: contain;
    }

    .dots-wrap {
        position: relative;
    }

    .dots-btn {
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 10px;
        background: #fff0f5;
        color: #d66f8d;
        font-size: 24px;
        font-weight: bold;
        line-height: 1;
        cursor: pointer;
    }

    .dots-btn:hover {
        background: #f9dbe6;
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
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
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

    /* ================= MAIN ================= */

    .main {
        margin-left: 235px;
        width: calc(100% - 235px);
        padding: 30px 35px;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .topbar h1 {
        font-size: 25px;
        color: #444;
        margin-bottom: 6px;
    }

    .topbar p {
        color: #999;
        font-size: 14px;
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 8px 14px;
        border-radius: 30px;
        border: 1px solid #f1dce3;
    }

    .profile-icon {
        width: 34px;
        height: 34px;
        background: #f5b4c7;
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
    }

    .profile span {
        font-size: 13px;
        font-weight: bold;
    }

    /* ================= STAT CARDS ================= */

    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border: 1px solid #f2dfe5;
        border-radius: 15px;
        padding: 20px;
    }

    .stat-title {
        color: #999;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 27px;
        font-weight: bold;
        color: #444;
    }

    .stat-info {
        margin-top: 8px;
        font-size: 12px;
        color: #d66f8d;
    }

    .stat-card.pink {
        border-top: 4px solid #f0a5ba;
    }

    .stat-card.purple {
        border-top: 4px solid #c7a5d8;
    }

    .stat-card.green {
        border-top: 4px solid #9bcbb0;
    }

    .stat-card.orange {
        border-top: 4px solid #e9b17d;
    }

    /* ================= CONTENT ================= */

    .content-card {
        background: white;
        border: 1px solid #f2dfe5;
        border-radius: 15px;
        padding: 22px;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .content-header h2 {
        font-size: 18px;
        color: #444;
    }

    .content-header a {
        text-decoration: none;
        color: #d66f8d;
        font-size: 13px;
        font-weight: bold;
    }

    .content-header a:hover {
        text-decoration: underline;
    }

    /* ================= TABLE ================= */

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    th {
        text-align: left;
        padding: 13px 10px;
        font-size: 12px;
        color: #999;
        border-bottom: 1px solid #f0e2e7;
        font-weight: bold;
    }

    td {
        padding: 15px 10px;
        font-size: 13px;
        border-bottom: 1px solid #f5eaee;
    }

    tbody tr:hover {
        background: #fff9fb;
    }

    .order-id {
        color: #d66f8d;
        font-weight: bold;
    }

    .status {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .status.menunggu {
        background: #fff0d9;
        color: #c27b25;
    }

    .status.dikonfirmasi {
        background: #eee5fa;
        color: #8657ad;
    }

    .status.sedang {
        background: #e7edff;
        color: #526eb3;
    }

    .status.siap {
        background: #e3f7ed;
        color: #398363;
    }

    .status.selesai {
        background: #dff4e7;
        color: #328057;
    }

    .action {
        text-decoration: none;
        color: #d66f8d;
        font-size: 12px;
        font-weight: bold;
    }

    .empty {
        text-align: center;
        padding: 35px;
        color: #aaa;
    }

    /* ================= RESPONSIVE ================= */

    @media (max-width: 1100px) {
        .stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {

        .sidebar {
            display: none;
        }

        .mobile-bar {
            display: flex;
        }

        .main {
            margin-left: 0;
            width: 100%;
            padding: 80px 16px 20px;
        }

        .topbar h1 {
            font-size: 21px;
        }

        .profile span {
            display: none;
        }
    }

    @media (max-width: 500px) {

        .stats {
            grid-template-columns: 1fr;
        }

        .topbar {
            align-items: flex-start;
        }

        .topbar h1 {
            font-size: 19px;
        }

        .content-card {
            padding: 15px;
        }
    }
</style>

</head>

<body>

@php
$pesananTerbaru = \App\Models\Pesanan::with([
'user',
'detailPesanans.produk',
'pembayaran',
'jadwalPengambilan'
])
->orderBy('id_pesanan', 'desc')
->take(5)
->get();

$totalPesanan = \App\Models\Pesanan::count();

$pesananDiproses = \App\Models\Pesanan::whereIn(
    'status_pesanan',
    ['dikonfirmasi', 'sedang dibuat', 'diproses']
)->count();

$pesananSelesai = \App\Models\Pesanan::where(
    'status_pesanan',
    'selesai'
)->count();

$pesananMenunggu = \App\Models\Pesanan::whereIn(
    'status_pesanan',
    ['menunggu', 'menunggu konfirmasi']
)->count();

@endphp

<!-- ================= MOBILE BAR ================= -->

<div class="mobile-bar">

<img
    src="{{ asset('images/logo_dwiera_jpg.jpeg') }}"
    alt="Logo Dwiera"
>

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

        <a href="{{ route('admin') }}" class="active">
            <span class="menu-icon">⌂</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.pesanan') }}">
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

<div class="layout">

<!-- ================= SIDEBAR ================= -->

<aside class="sidebar">

    <!-- LOGO SIDEBAR DIHILANGKAN -->

    <div class="admin-label">
        ADMIN PANEL
    </div>

    <nav class="menu">

        <a href="{{ route('admin') }}" class="active">
            <span class="menu-icon">⌂</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.pesanan') }}">
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

    </nav>

    <div class="logout">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit">
                <span>↪</span>
                <span>Logout</span>
            </button>

        </form>

    </div>

</aside>


<!-- ================= MAIN ================= -->

<main class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <div>
            <h1>Dashboard Admin</h1>
            <p>Kelola operasional pre-order kue ulang tahun Dwiera.</p>
        </div>

        <div class="profile">

            <div class="profile-icon">
                A
            </div>

            <span>Admin</span>

        </div>

    </div>


    <!-- ================= STAT CARDS ================= -->

    <section class="stats">

        <div class="stat-card pink">

            <div class="stat-title">
                Total Pesanan
            </div>

            <div class="stat-number">
                {{ $totalPesanan }}
            </div>

            <div class="stat-info">
                Semua pesanan
            </div>

        </div>


        <div class="stat-card purple">

            <div class="stat-title">
                Pesanan Diproses
            </div>

            <div class="stat-number">
                {{ $pesananDiproses }}
            </div>

            <div class="stat-info">
                Perlu dipantau
            </div>

        </div>


        <div class="stat-card green">

            <div class="stat-title">
                Pesanan Selesai
            </div>

            <div class="stat-number">
                {{ $pesananSelesai }}
            </div>

            <div class="stat-info">
                Pesanan selesai
            </div>

        </div>


        <div class="stat-card orange">

            <div class="stat-title">
                Pesanan Baru
            </div>

            <div class="stat-number">
                {{ $pesananMenunggu }}
            </div>

            <div class="stat-info">
                Menunggu konfirmasi
            </div>

        </div>

    </section>


    <!-- ================= PESANAN TERBARU ================= -->

    <section class="content-card">

        <div class="content-header">

            <h2>
                Pesanan Terbaru
            </h2>

            <a href="{{ route('admin.pesanan') }}">
                Lihat Semua →
            </a>

        </div>


        <div class="table-wrapper">

            @if($pesananTerbaru->count() > 0)

                <table>

                    <thead>

                        <tr>
                            <th>No. Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($pesananTerbaru as $item)

                            @php
                                $status = strtolower($item->status_pesanan ?? 'menunggu');

                                if ($status === 'sedang dibuat' || $status === 'diproses') {
                                    $statusClass = 'sedang';
                                } elseif ($status === 'dikonfirmasi') {
                                    $statusClass = 'dikonfirmasi';
                                } elseif ($status === 'siap diambil') {
                                    $statusClass = 'siap';
                                } elseif ($status === 'selesai') {
                                    $statusClass = 'selesai';
                                } else {
                                    $statusClass = 'menunggu';
                                }
                            @endphp

                            <tr>

                                <td>
                                    <span class="order-id">
                                        #DW{{ str_pad($item->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->user->nama_users ?? '-' }}
                                </td>

                                <td>

                                    @if($item->detailPesanans->count() > 0)

                                        {{ $item->detailPesanans->first()->produk->nama_kue ?? '-' }}

                                        @if($item->detailPesanans->count() > 1)
                                            + {{ $item->detailPesanans->count() - 1 }} lainnya
                                        @endif

                                    @else
                                        -
                                    @endif

                                </td>

                                <td>

                                    <span class="status {{ $statusClass }}">
                                        {{ ucfirst($item->status_pesanan ?? 'Menunggu') }}
                                    </span>

                                </td>

                                <td>
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route('admin.pesanan') }}"
                                        class="action"
                                    >
                                        Lihat
                                    </a>

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

    </section>

</main>
```

</div>

<!-- ================= SCRIPT MENU TITIK TIGA ================= -->

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
