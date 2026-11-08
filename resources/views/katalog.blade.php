<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Katalog - Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff8fa;
            color: #333;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: white;
            border-right: 1px solid #f1dfe5;
            padding: 28px 18px;
            z-index: 10;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h1 {
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 28px;
            color: #c9577c;
            margin-bottom: 2px;
        }

        .logo p {
            font-size: 9px;
            color: #999;
            letter-spacing: 1px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 9px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #fde8ef;
            color: #c9577c;
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }

        .logout-area {
            position: absolute;
            bottom: 25px;
            left: 18px;
            right: 18px;
        }

        .logout {
            width: 100%;
            border: none;
            background: transparent;
            color: #777;
            padding: 12px;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            border-radius: 9px;
        }

        .logout:hover {
            background: #fde8ef;
            color: #c9577c;
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
            border-bottom: 1px solid #f1dfe5;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .mobile-logo {
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 26px;
            color: #c9577c;
        }

        .mobile-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dots-wrap {
            position: relative;
        }

        .dots-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            background: #fde8ef;
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
            display: flex;
            align-items: center;
            gap: 10px;
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
        .dots-menu button:hover,
        .dots-menu a.active {
            background: #fde8ef;
            color: #c9577c;
        }

        .dots-menu a.active {
            font-weight: bold;
        }

        .dots-menu hr {
            border: none;
            border-top: 1px solid #f4e8ec;
            margin: 6px 0;
        }

        /* ================= MAIN ================= */

        .main {
            margin-left: 220px;
            padding: 28px 35px 50px;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 22px;
            color: #333;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #777;
            font-size: 13px;
        }

        .user-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fde8ef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c9577c;
        }

        /* ================= HEADER ================= */

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 25px;
            border: 1px solid #f2e2e7;
        }

        .page-header h1 {
            color: #a94668;
            font-family: Georgia, serif;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #777;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ================= SEARCH ================= */

        .search-box {
            background: white;
            border: 1px solid #f2e2e7;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
            color: #555;
        }

        .search-box input::placeholder {
            color: #aaa;
        }

        /* ================= PRODUCT HEADER ================= */

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .products-header h2 {
            font-size: 19px;
            color: #444;
        }

        .products-header span {
            color: #999;
            font-size: 12px;
        }

        /* ================= PRODUCT GRID ================= */

        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
            align-items: stretch;
        }

        .product-card {
            min-width: 0;
            background: white;
            border: 1px solid #f2e2e7;
            border-radius: 14px;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .product-card:hover {
            border-color: #edc4d2;
            box-shadow: 0 8px 22px rgba(190, 100, 130, 0.10);
        }

        .cake-image {
            width: 100%;
            height: 200px;
            background: #fff0f4;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .cake-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cake-placeholder {
            font-size: 75px;
        }

        .card-content {
            padding: 18px;
            min-width: 0;
        }

        .card-content h3 {
            color: #444;
            font-size: 16px;
            line-height: 1.4;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .description {
            color: #777;
            font-size: 13px;
            line-height: 1.6;
            min-height: 42px;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        .size {
            color: #999;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .price {
            color: #d05f83;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.4;
            margin-bottom: 15px;
            overflow-wrap: anywhere;
        }

        /* ================= BUTTON DETAIL ================= */

        .btn {
            display: block;
            width: 100%;
            padding: 12px 10px;
            background: #d9688c;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #c9577c;
        }

        /* ================= CART LINK ================= */

        .cart-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 14px;
            background: #fde8ef;
            color: #c9577c;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .cart-link:hover {
            background: #fbd8e4;
        }

        /* ================= NOTIFICATIONS ================= */

        .cart-notice {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.5;
        }

        .cart-notice.success {
            background: #e9f9ef;
            color: #237747;
        }

        .cart-notice.error {
            background: #fff0f0;
            color: #a92e2e;
        }

        /* ================= EMPTY ================= */

        .empty {
            background: white;
            border: 1px solid #f2e2e7;
            border-radius: 14px;
            padding: 50px 20px;
            text-align: center;
        }

        .empty-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .empty h3 {
            color: #c9577c;
            margin-bottom: 8px;
        }

        .empty p {
            color: #888;
            font-size: 13px;
            line-height: 1.5;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 1200px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding-left: 25px;
                padding-right: 25px;
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
                padding: 80px 15px 35px;
            }

            .topbar {
                align-items: flex-start;
            }

            .topbar h2 {
                font-size: 20px;
            }

            .user-info {
                display: none;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .cake-image {
                height: 150px;
            }

            .card-content {
                padding: 13px;
            }

            .card-content h3 {
                font-size: 14px;
            }

            .description,
            .size {
                font-size: 12px;
            }

            .price {
                font-size: 15px;
            }
        }

        @media (max-width: 380px) {
            .products {
                grid-template-columns: 1fr;
            }

            .cake-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>

    <!-- ================= MOBILE BAR ================= -->

    <div class="mobile-bar">

        <div class="mobile-logo">Dwiera</div>

        <div class="mobile-actions">

            <div class="dots-wrap">

                <button type="button" class="dots-btn" id="dotsBtn"
                    aria-label="Buka menu" aria-expanded="false">⋮</button>

                <div class="dots-menu" id="dotsMenu">

                    <a href="{{ route('pelanggan') }}">
                        <span class="menu-icon">⌂</span>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('katalog') }}" class="active">
                        <span class="menu-icon">▦</span>
                        <span>Katalog</span>
                    </a>

                    <a href="{{ route('pesanan.pelanggan') }}">
                        <span class="menu-icon">♧</span>
                        <span>Pesanan Saya</span>
                    </a>

                    <a href="{{ route('profil') }}">
                        <span class="menu-icon">♙</span>
                        <span>Profil</span>
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

    </div>

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">
            <h1>Dwiera</h1>
            <p>BIRTHDAY CAKE &amp; MORE</p>
        </div>

        <nav class="menu">

            <a href="{{ route('pelanggan') }}">
                <span class="menu-icon">⌂</span>
                <span>Beranda</span>
            </a>

            <a href="{{ route('katalog') }}" class="active">
                <span class="menu-icon">▦</span>
                <span>Katalog</span>
            </a>

            <a href="{{ route('pesanan.pelanggan') }}">
                <span class="menu-icon">♧</span>
                <span>Pesanan Saya</span>
            </a>

            <a href="{{ route('profil') }}">
                <span class="menu-icon">♙</span>
                <span>Profil</span>
            </a>

        </nav>

        <div class="logout-area">
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout">
                    ↪ &nbsp; Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- ================= MAIN ================= -->

    <main class="main">

        <div class="topbar">

            <h2>Katalog</h2>

            <div class="topbar-actions">

                <a href="{{ route('keranjang.index') }}" class="cart-link">
                    🛒 Keranjang
                </a>

                <div class="user-info">
                    <div class="user-icon">♙</div>
                    <span>Pelanggan</span>
                </div>

            </div>

        </div>

        @if(session('success'))
            <div class="cart-notice success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="cart-notice error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="cart-notice error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- ================= PAGE HEADER ================= -->

        <section class="page-header">

            <h1>Kue Ulang Tahun</h1>

            <p>
                Pilih kue favoritmu untuk membuat momen spesial
                menjadi lebih berkesan.
            </p>

        </section>

        <!-- ================= SEARCH ================= -->

        <div class="search-box">
            <input
                type="text"
                id="searchInput"
                placeholder="Cari kue, misalnya Strawberry Cake..."
                autocomplete="off"
            >
        </div>

        <!-- ================= PRODUCT HEADER ================= -->

        <div class="products-header">
            <h2>Semua Kue</h2>

            <span>
                {{ $produk->count() }} produk tersedia
            </span>
        </div>

        @if($produk->count() > 0)

            <!-- ================= PRODUCT GRID ================= -->

            <div class="products" id="productList">

                @foreach($produk as $item)

                    <div
                        class="card product-card"
                        data-name="{{ strtolower($item->nama_kue) }}"
                    >

                        <div class="cake-image">

                            @if(!empty($item->gambar))

                                <img
                                    src="{{ asset('images/produk/' . $item->gambar) }}"
                                    alt="{{ $item->nama_kue }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="cake-placeholder">
                                    🎂
                                </div>

                            @endif

                        </div>

                        <div class="card-content">

                            <h3>{{ $item->nama_kue }}</h3>

                            <p class="description">
                                {{ $item->deskripsi }}
                            </p>

                            <p class="size">
                                Ukuran: {{ $item->ukuran }}
                            </p>

                            <div class="price">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>

                            <a
                                href="{{ route('produk.detail', $item->id_produk) }}"
                                class="btn"
                            >
                                Lihat Detail
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

            <!-- ================= SEARCH EMPTY RESULT ================= -->

            <div
                id="noSearchResult"
                class="empty"
                style="display: none; margin-top: 20px;"
            >

                <div class="empty-icon">🔍</div>

                <h3>Kue Tidak Ditemukan</h3>

                <p>
                    Coba gunakan kata pencarian yang berbeda.
                </p>

            </div>

        @else

            <div class="empty">

                <div class="empty-icon">🎂</div>

                <h3>Belum Ada Kue</h3>

                <p>
                    Data kue belum tersedia di database.
                </p>

            </div>

        @endif

    </main>

    <!-- ================= SCRIPT ================= -->

    <script>
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.product-card');
        const noSearchResult = document.getElementById('noSearchResult');

        if (searchInput) {
            searchInput.addEventListener('input', function () {

                const keyword = this.value.toLowerCase().trim();
                let visible = 0;

                productCards.forEach(function (card) {

                    const name = card.dataset.name || '';

                    if (name.includes(keyword)) {
                        card.style.display = '';
                        visible++;
                    } else {
                        card.style.display = 'none';
                    }

                });

                if (noSearchResult) {
                    noSearchResult.style.display =
                        visible === 0 ? 'block' : 'none';
                }

            });
        }

        // MENU TITIK TIGA
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