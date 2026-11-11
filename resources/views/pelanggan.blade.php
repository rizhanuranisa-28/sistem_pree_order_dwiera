<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        :root {
            --pink: #d9688c;
            --pink-dark: #b94e73;
            --pink-light: #fde8ef;
            --background: #fff8fa;
            --text: #41383b;
            --muted: #8b8084;
            --border: #f1dfe5;
        }

        body {
            background: var(--background);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 225px;
            background: #fff;
            border-right: 1px solid var(--border);
            padding: 28px 18px;
            z-index: 10;
        }

        .logo {
            text-align: center;
            margin-bottom: 38px;
        }

        .logo h1 {
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 32px;
            color: var(--pink-dark);
        }

        .logo p {
            font-size: 9px;
            color: #a39a9e;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 15px;
            border-radius: 10px;
            color: #756b70;
            font-size: 14px;
            transition: .2s;
        }

        .menu a:hover,
        .menu a.active {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 18px;
        }

        .logout-area {
            position: absolute;
            bottom: 25px;
            left: 18px;
            right: 18px;
        }

        .logout {
            display: block;
            width: 100%;
            padding: 12px 15px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #756b70;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .logout:hover {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        /* BARU: BAR ATAS + MENU TITIK TIGA (HP) */
        .mobile-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            padding: 0 16px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .mobile-logo {
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 26px;
            color: var(--pink-dark);
        }

        .dots-wrap {
            position: relative;
        }

        .dots-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            background: var(--pink-light);
            color: var(--pink-dark);
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
            border: 1px solid var(--border);
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
            color: #756b70;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .dots-menu a:hover,
        .dots-menu button:hover,
        .dots-menu a.active {
            color: var(--pink-dark);
            background: var(--pink-light);
        }

        .dots-menu a.active {
            font-weight: bold;
        }

        .dots-menu hr {
            border: none;
            border-top: 1px solid #f4e8ec;
            margin: 6px 0;
        }

        /* MAIN */
        .main {
            margin-left: 225px;
            padding: 28px 35px 50px;
            max-width: 1600px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 23px;
        }

        .topbar p {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 13px;
            border: 1px solid var(--border);
            border-radius: 30px;
            background: #fff;
            color: #746970;
            font-size: 13px;
        }

        .user-icon {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--pink-light);
            color: var(--pink-dark);
        }

        /* HERO */
        .hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            min-height: 280px;
            padding: 38px 40px;
            margin-bottom: 36px;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(110deg, #f9dce5, #fff0f4);
        }

        .hero-text {
            position: relative;
            z-index: 2;
            max-width: 58%;
        }

        .eyebrow {
            display: inline-block;
            color: var(--pink-dark);
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .hero h1 {
            margin: 13px 0;
            color: #994363;
            font-family: Georgia, serif;
            font-size: clamp(27px, 3vw, 39px);
            line-height: 1.2;
        }

        .hero p {
            max-width: 410px;
            margin-bottom: 22px;
            color: #77656d;
            font-size: 14px;
            line-height: 1.8;
        }

        .hero-btn {
            display: inline-block;
            padding: 12px 21px;
            border-radius: 9px;
            background: var(--pink);
            color: white;
            font-size: 13px;
            font-weight: bold;
            transition: .2s;
        }

        .hero-btn:hover,
        .product-btn:hover {
            background: var(--pink-dark);
        }

        .hero-image {
            width: 38%;
            height: 220px;
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255, 255, 255, .45);
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .hero-placeholder {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            color: #c9577c;
            font-size: 75px;
        }

        /* SECTION */
        .section {
            margin-bottom: 36px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .section-header h2 {
            font-size: 20px;
        }

        .section-header p {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .see-all {
            flex-shrink: 0;
            color: var(--pink-dark);
            font-size: 12px;
            font-weight: bold;
        }

        .see-all:hover {
            text-decoration: underline;
        }

        /* CATEGORY */
        .categories {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .category {
            display: block;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            text-align: center;
            transition: .2s;
        }

        .category:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(185, 78, 115, .09);
        }

        .category-image {
            width: 100%;
            height: 115px;
            margin-bottom: 12px;
            border-radius: 10px;
            overflow: hidden;
            background: #fff0f4;
        }

        .category-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category h3 {
            padding-bottom: 5px;
            color: #51444a;
            font-size: 13px;
        }

        /* PRODUCTS */
        .products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .product {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: #fff;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(185, 78, 115, .10);
        }

        .product-image {
            height: 175px;
            overflow: hidden;
            background: #fff0f4;
        }

        .product-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .product:hover .product-image img {
            transform: scale(1.04);
        }

        .image-placeholder {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            font-size: 65px;
        }

        .product-content {
            padding: 15px;
        }

        .product-content h3 {
            margin-bottom: 8px;
            color: #44383e;
            font-size: 14px;
            line-height: 1.5;
        }

        .description {
            min-height: 35px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .size {
            margin-bottom: 10px;
            color: #92868c;
            font-size: 11px;
        }

        .price {
            margin-bottom: 14px;
            color: var(--pink-dark);
            font-size: 16px;
            font-weight: bold;
        }

        .product-btn {
            display: block;
            padding: 11px 8px;
            border-radius: 8px;
            background: var(--pink);
            color: white;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            transition: .2s;
        }

        /* EMPTY */
        .empty {
            grid-column: 1 / -1;
            padding: 45px 20px;
            border: 1px dashed #e9bdcd;
            border-radius: 15px;
            background: white;
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 12px;
            font-size: 45px;
        }

        .empty h3 {
            margin-bottom: 8px;
            color: var(--pink-dark);
        }

        .empty p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .main {
                padding-right: 24px;
                padding-left: 24px;
            }

            .products {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .categories {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .category-image {
                height: 95px;
            }
        }

        /* DIUBAH: di HP, sidebar disembunyikan dan diganti menu titik tiga */
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

            .hero {
                padding: 28px;
            }

            .hero-image {
                height: 180px;
            }

            .categories {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .topbar {
                margin-bottom: 20px;
            }

            .topbar h2 {
                font-size: 21px;
            }

            .user-info {
                padding: 5px 8px;
                font-size: 11px;
            }

            .user-icon {
                width: 29px;
                height: 29px;
            }

            .hero {
                min-height: 0;
                padding: 24px 20px;
                flex-direction: column;
                align-items: stretch;
            }

            .hero-text {
                max-width: 100%;
            }

            .hero h1 {
                font-size: 28px;
            }

            .hero p {
                font-size: 13px;
            }

            .hero-image {
                width: 100%;
                height: 180px;
            }

            .categories {
                gap: 10px;
            }

            .category-image {
                height: 115px;
            }

            .products {
                gap: 11px;
            }

            .product-image {
                height: 140px;
            }

            .product-content {
                padding: 12px;
            }

            .product-content h3 {
                font-size: 13px;
            }

            .description {
                font-size: 11px;
            }

            .price {
                font-size: 14px;
            }
        }

        @media (max-width: 370px) {
            .user-info span {
                display: none;
            }

            .category-image {
                height: 95px;
            }

            .product-image {
                height: 115px;
            }
        }
    </style>
</head>

<body>

    <!-- BARU: BAR ATAS + MENU TITIK TIGA (HP) -->
    <div class="mobile-bar">
        <div class="mobile-logo">Dwiera</div>

        <div class="dots-wrap">
            <button type="button" class="dots-btn" id="dotsBtn"
                    aria-label="Buka menu" aria-expanded="false">⋮</button>

            <div class="dots-menu" id="dotsMenu">
                <a href="{{ route('pelanggan') }}" class="active">
                    <span class="menu-icon">⌂</span>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('katalog') }}">
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

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <h1>Dwiera</h1>
            <p>BIRTHDAY CAKE & MORE</p>
        </div>

        <nav class="menu">
            <a href="{{ route('pelanggan') }}" class="active">
                <span class="menu-icon">⌂</span>
                <span>Beranda</span>
            </a>

            <a href="{{ route('katalog') }}">
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
                <button type="submit" class="logout">↪ &nbsp; Logout</button>
            </form>
        </div>

    </aside>

    <!-- MAIN -->
    <main class="main">

        <header class="topbar">
            <div>
                <h2>Beranda</h2>
                <p>Temukan kue spesial untuk momen istimewamu ✨</p>
            </div>

            <div class="user-info">
                <div class="user-icon">♙</div>
                <span>Pelanggan</span>
            </div>
        </header>

        <!-- HERO BANNER -->
        <section class="hero">

            <div class="hero-text">
                <span class="eyebrow">MADE WITH LOVE · DWIERA CAKE</span>

                <h1>
                    Kue Ulang Tahun<br>
                    untuk Momen Spesial
                </h1>

                <p>
                    Rayakan hari istimewamu dengan kue cantik dan lezat.
                    Pilih favoritmu, pesan dengan mudah, dan ambil sesuai jadwal.
                </p>

                <a href="{{ route('katalog') }}" class="hero-btn">
                    Jelajahi Katalog &nbsp; →
                </a>
            </div>

            <div class="hero-image">
                @php
                    $heroProduk = isset($produk) ? $produk->first(function ($item) {
                        return !empty($item->gambar);
                    }) : null;
                @endphp

                @if($heroProduk)
                    <img
                        src="{{ $heroProduk->gambar_url }}"
                        alt="{{ $heroProduk->nama_kue }}"
                    >
                @else
                    <div class="hero-placeholder">🎂</div>
                @endif
            </div>

        </section>

        <!-- KATEGORI BERDASARKAN PRODUK -->
        <section class="section">

            <div class="section-header">
                <div>
                    <h2>Kue Favorit 🎀</h2>
                    <p>Pilihan kue untuk melengkapi hari spesialmu.</p>
                </div>

                <a href="{{ route('katalog') }}" class="see-all">
                    Lihat Semua →
                </a>
            </div>

            <div class="categories">

                @forelse(isset($produk) ? $produk->take(4) : collect() as $item)
                    <a href="{{ route('produk.detail', $item->id_produk) }}"
                       class="category">

                        <div class="category-image">
                            @if(!empty($item->gambar))
                                <img
                                    src="{{ $item->gambar_url }}"
                                    alt="{{ $item->nama_kue }}"
                                >
                            @else
                                <div class="image-placeholder">🎂</div>
                            @endif
                        </div>

                        <h3>{{ $item->nama_kue }}</h3>
                    </a>
                @empty
                    <div class="empty">
                        <div class="empty-icon">🎂</div>
                        <h3>Belum Ada Kue</h3>
                        <p>Produk aktif akan ditampilkan di sini.</p>
                    </div>
                @endforelse

            </div>
        </section>

        <!-- KUE PILIHAN -->
        <section class="section">

            <div class="section-header">
                <div>
                    <h2>Kue Pilihan Kami 🍰</h2>
                    <p>Temukan kue yang cocok untuk perayaanmu.</p>
                </div>

                <a href="{{ route('katalog') }}" class="see-all">
                    Lihat Semua →
                </a>
            </div>

            <div class="products">

                @forelse(isset($produk) ? $produk->take(4) : collect() as $item)
                    <article class="product">

                        <div class="product-image">
                            @if(!empty($item->gambar))
                                <img
                                    src="{{ $item->gambar_url }}"
                                    alt="{{ $item->nama_kue }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="image-placeholder">🎂</div>
                            @endif
                        </div>

                        <div class="product-content">

                            <h3>{{ $item->nama_kue }}</h3>

                            <p class="description">
                                {{ $item->deskripsi ?: 'Kue spesial untuk momen istimewa.' }}
                            </p>

                            <p class="size">
                                Ukuran: {{ $item->ukuran ?: '-' }}
                            </p>

                            <div class="price">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>

                            <a
                                href="{{ route('produk.detail', $item->id_produk) }}"
                                class="product-btn"
                            >
                                Pesan Sekarang
                            </a>

                        </div>
                    </article>
                @empty
                    <div class="empty">
                        <div class="empty-icon">🧁</div>
                        <h3>Belum Ada Produk</h3>
                        <p>
                            Silakan tambahkan produk aktif melalui halaman Admin
                            agar produk tampil di dashboard.
                        </p>
                    </div>
                @endforelse

            </div>
        </section>

    </main>

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