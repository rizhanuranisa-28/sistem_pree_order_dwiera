<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

```
<title>Kelola Produk - Dwiera</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background: #f7f7f7;
        color: #333;
    }

    .layout {
        display: flex;
        min-height: 100vh;
    }

    /* SIDEBAR */

    .sidebar {
        width: 235px;
        background: #ffffff;
        border-right: 1px solid #eeeeee;
        padding: 25px 15px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
    }

    .admin-label {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        color: #5b3030;
        margin-bottom: 35px;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .menu a {
        text-decoration: none;
        color: #555;
        padding: 13px 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        transition: 0.2s;
    }

    .menu a:hover {
        background: #f5eeee;
    }

    .menu a.active {
        background: #eadada;
        color: #5b3030;
        font-weight: bold;
    }

    .menu-icon {
        width: 22px;
        text-align: center;
    }

    .logout-form {
        margin-top: 8px;
    }

    .logout-button {
        width: 100%;
        border: none;
        background: transparent;
        cursor: pointer;
        text-align: left;
        color: #555;
        padding: 13px 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .logout-button:hover {
        background: #f5eeee;
    }

    /* MAIN */

    .main {
        margin-left: 235px;
        width: calc(100% - 235px);
        padding: 35px;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        gap: 20px;
    }

    .topbar h1 {
        font-size: 27px;
        color: #3d2525;
    }

    .topbar p {
        margin-top: 7px;
        color: #888;
        font-size: 14px;
    }

    .btn-add {
        background: #6b3b3b;
        color: white;
        text-decoration: none;
        padding: 12px 18px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: bold;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #542d2d;
    }

    /* ALERT */

    .alert-success {
        background: #e7f5e9;
        color: #2e7d32;
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-error {
        background: #f8e4e4;
        color: #a33a3a;
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    /* TABLE */

    .card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    th {
        text-align: left;
        padding: 14px 12px;
        font-size: 13px;
        color: #777;
        border-bottom: 1px solid #eeeeee;
    }

    td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        background: #f1e8e8;
    }

    .no-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background: #f1e8e8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .product-name {
        font-weight: bold;
        color: #3d2525;
    }

    /* UKURAN DAN HARGA */

    .size-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
        min-width: 180px;
    }

    .size-item {
        padding: 9px 10px;
        border-radius: 8px;
        background: #fff5f8;
        border: 1px solid #f0dce5;
    }

    .size-name {
        font-weight: bold;
        color: #5b3030;
        margin-bottom: 4px;
    }

    .size-price {
        color: #a64d72;
        font-weight: bold;
        font-size: 13px;
    }

    .size-status {
        display: inline-block;
        font-size: 11px;
        margin-top: 5px;
        padding: 3px 7px;
        border-radius: 10px;
    }

    .size-status-aktif {
        background: #e7f5e9;
        color: #2e7d32;
    }

    .size-status-nonaktif {
        background: #f8e4e4;
        color: #a33a3a;
    }

    .no-size {
        color: #999;
        font-size: 13px;
    }

    /* STATUS PRODUK */

    .status {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-aktif {
        background: #e7f5e9;
        color: #2e7d32;
    }

    .status-nonaktif {
        background: #f8e4e4;
        color: #a33a3a;
    }

    /* ACTION */

    .actions {
        display: flex;
        gap: 7px;
    }

    .btn-edit,
    .btn-delete {
        border: none;
        padding: 8px 12px;
        border-radius: 7px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-edit {
        background: #eee5e5;
        color: #5b3030;
    }

    .btn-edit:hover {
        background: #e3d7d7;
    }

    .btn-delete {
        background: #f7dddd;
        color: #a33a3a;
    }

    .btn-delete:hover {
        background: #f1cccc;
    }

    /* EMPTY */

    .empty {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }

    .empty h3 {
        margin-bottom: 8px;
        color: #777;
    }

    /* RESPONSIVE */

    @media (max-width: 768px) {
        .sidebar {
            width: 190px;
        }

        .main {
            margin-left: 190px;
            width: calc(100% - 190px);
            padding: 20px;
        }

        .topbar {
            align-items: flex-start;
            gap: 15px;
            flex-direction: column;
        }

        .topbar h1 {
            font-size: 23px;
        }
    }

    @media (max-width: 600px) {
        .sidebar {
            width: 75px;
            padding: 20px 8px;
        }

        .admin-label {
            font-size: 10px;
            margin-bottom: 25px;
        }

        .menu a {
            justify-content: center;
            padding: 12px 5px;
        }

        .menu a span:last-child {
            display: none;
        }

        .logout-button {
            justify-content: center;
            padding: 12px 5px;
        }

        .logout-button span:last-child {
            display: none;
        }

        .main {
            margin-left: 75px;
            width: calc(100% - 75px);
            padding: 18px;
        }
    }
</style>
```

</head>

<body>

<div class="layout">

```
<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="admin-label">
        ADMIN PANEL
    </div>

    <nav class="menu">

        <a href="{{ route('admin') }}">
            <span class="menu-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.pesanan') }}">
            <span class="menu-icon">📋</span>
            <span>Pesanan</span>
        </a>

        <a href="{{ route('admin.produk') }}" class="active">
            <span class="menu-icon">🎂</span>
            <span>Produk</span>
        </a>

        <a href="{{ route('admin.pembayaran') }}">
            <span class="menu-icon">💳</span>
            <span>Pembayaran</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf

            <button type="submit" class="logout-button">
                <span class="menu-icon">🚪</span>
                <span>Logout</span>
            </button>
        </form>

    </nav>

</aside>

<!-- MAIN CONTENT -->

<main class="main">

    <div class="topbar">

        <div>
            <h1>Kelola Produk</h1>

            <p>
                Kelola data kue ulang tahun Dwiera.
            </p>
        </div>

        <a href="{{ route('admin.produk.create') }}" class="btn-add">
            + Tambah Kue
        </a>

    </div>

    <!-- SUCCESS MESSAGE -->

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- TABLE -->

    <div class="card">

        @if($produk->count() > 0)

            <table>

                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Kue</th>
                        <th>Ukuran dan Harga</th>
                        <th>Status Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($produk as $item)

                        <tr>

                            <!-- GAMBAR -->

                            <td>
                                @if($item->gambar)

                                    <img
                                        src="{{ $item->gambar_url }}"
                                        alt="{{ $item->nama_kue }}"
                                        class="product-image"
                                    >

                                @else

                                    <div class="no-image">
                                        🎂
                                    </div>

                                @endif
                            </td>

                            <!-- NAMA KUE -->

                            <td>
                                <span class="product-name">
                                    {{ $item->nama_kue }}
                                </span>
                            </td>

                            <!-- UKURAN DAN HARGA -->

                            <td>

                                <div class="size-list">

                                    @forelse($item->ukuranProduks as $ukuran)

                                        <div class="size-item">

                                            <div class="size-name">
                                                {{ $ukuran->nama_ukuran }}
                                            </div>

                                            <div class="size-price">
                                                Rp {{ number_format($ukuran->harga, 0, ',', '.') }}
                                            </div>

                                            <span class="size-status {{ $ukuran->status === 'aktif' ? 'size-status-aktif' : 'size-status-nonaktif' }}">
                                                {{ ucfirst($ukuran->status) }}
                                            </span>

                                        </div>

                                    @empty

                                        <span class="no-size">
                                            Belum ada ukuran
                                        </span>

                                    @endforelse

                                </div>

                            </td>

                            <!-- STATUS PRODUK -->

                            <td>

                                @if(strtolower($item->status) === 'aktif')

                                    <span class="status status-aktif">
                                        Aktif
                                    </span>

                                @else

                                    <span class="status status-nonaktif">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            <!-- AKSI -->

                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('admin.produk.edit', $item->id_produk) }}"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.produk.destroy', $item->id_produk) }}"
                                        method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">

                <h3>Belum ada produk</h3>

                <p>
                    Data kue belum tersedia.
                </p>

            </div>

        @endif

    </div>

</main>
```

</div>

</body>
</html>
