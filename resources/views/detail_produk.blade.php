<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_kue }} - Dwiera</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background: #fff8fa;
        color: #333;
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 220px;
        height: 100vh;
        background: #fff;
        border-right: 1px solid #f1dce3;
        padding: 35px 18px;
        z-index: 10;
    }

    .logo {
        text-align: center;
        margin-bottom: 45px;
    }

    .logo h1 {
        font-family: Georgia, serif;
        font-style: italic;
        font-size: 42px;
        color: #cf6285;
        margin-bottom: 4px;
    }

    .logo p {
        font-size: 10px;
        letter-spacing: 2px;
        color: #b86f87;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .menu a,
    .logout-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 15px;
        border-radius: 12px;
        text-decoration: none;
        color: #806b75;
        font-size: 15px;
        transition: 0.2s;
    }

    .menu a:hover,
    .logout-btn:hover {
        background: #fff0f4;
        color: #cf6285;
    }

    .menu a.active {
        background: #fde6ed;
        color: #d0527a;
        font-weight: bold;
    }

    .icon {
        width: 22px;
        text-align: center;
        font-size: 19px;
    }

    .logout-area {
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 30px;
        border-top: 1px solid #f2dfe5;
        padding-top: 20px;
    }

    .logout-btn {
        width: 100%;
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 15px;
        text-align: left;
    }

    .mobile-bar {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
        padding: 0 16px;
        background: #fff;
        border-bottom: 1px solid #f1dce3;
        align-items: center;
        justify-content: space-between;
        z-index: 50;
    }

    .mobile-logo {
        font-family: Georgia, serif;
        font-style: italic;
        font-size: 28px;
        color: #cf6285;
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
        color: #cf6285;
        font-size: 24px;
        font-weight: bold;
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
        border: 1px solid #f1dce3;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
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
        color: #806b75;
        font-size: 14px;
        text-align: left;
        cursor: pointer;
    }

    .dots-menu a:hover,
    .dots-menu button:hover {
        background: #fff0f4;
        color: #cf6285;
    }

    .dots-menu a.active {
        background: #fde6ed;
        color: #d0527a;
        font-weight: bold;
    }

    .dots-menu hr {
        border: none;
        border-top: 1px solid #f4e8ec;
        margin: 6px 0;
    }

    .main {
        margin-left: 220px;
        min-height: 100vh;
    }

    .topbar {
        height: 100px;
        background: #fff;
        border-bottom: 1px solid #f2dfe5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 45px;
    }

    .topbar h2 {
        font-size: 30px;
        color: #292936;
    }

    .customer {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #756b73;
        font-size: 15px;
    }

    .customer-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #fde5ed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .content {
        padding: 35px 45px 50px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 13px 20px;
        border: 1px solid #f5d5df;
        background: #fff1f5;
        color: #d0527a;
        border-radius: 12px;
        text-decoration: none;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .detail-card {
        background: #fff;
        border: 1px solid #f0dfe5;
        border-radius: 18px;
        padding: 28px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 35px;
        box-shadow: 0 5px 20px rgba(210,100,135,0.06);
    }

    .product-image {
        width: 100%;
        height: 470px;
        background: #ffe5ed;
        border-radius: 15px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-placeholder {
        font-size: 100px;
    }

    .detail {
        padding: 10px 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .detail h1 {
        font-family: Georgia, serif;
        font-size: 38px;
        color: #cf5d82;
        margin-bottom: 18px;
    }

    .description {
        color: #6f6970;
        font-size: 16px;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .info-box {
        border: 1px solid #f1dfe5;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 25px;
        background: #fffafb;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 0;
    }

    .info-row:not(:last-child) {
        border-bottom: 1px solid #f5e8ec;
    }

    .info-icon {
        width: 32px;
        font-size: 20px;
        text-align: center;
    }

    .info-label {
        width: 80px;
        font-weight: bold;
        color: #655d63;
    }

    .info-value {
        color: #666;
    }

    .status {
        display: inline-block;
        padding: 6px 13px;
        border-radius: 20px;
        background: #e3f5e8;
        color: #27834a;
        font-size: 13px;
        font-weight: bold;
    }

    .order-field {
        margin-bottom: 18px;
    }

    .order-field label {
        display: block;
        margin-bottom: 9px;
        color: #655d63;
        font-weight: bold;
    }

    .order-field select,
    .order-field input {
        width: 100%;
        padding: 14px;
        border: 1px solid #f1d1dc;
        border-radius: 10px;
        background: #fffafb;
        color: #49313b;
        font-size: 15px;
    }

    .order-field select:focus,
    .order-field input:focus {
        outline: 2px solid #f7c1d2;
    }

    .price {
        font-family: Georgia, serif;
        font-size: 32px;
        font-weight: bold;
        color: #cf5d82;
        margin-bottom: 22px;
    }

    .btn-order {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 10px;
        background: #d45f87;
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-order:hover {
        background: #c44f76;
    }

    .btn-order:disabled {
        background: #c9aab6;
        cursor: not-allowed;
    }

    .alert-message {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 10px;
        background: #e3f5e8;
        color: #27834a;
    }

    .alert-error {
        background: #fff0f0;
        color: #b42318;
    }

    @media (max-width: 900px) {
        .topbar {
            padding: 0 25px;
        }

        .content {
            padding: 25px;
        }

        .detail-card {
            grid-template-columns: 1fr;
        }

        .product-image {
            height: 400px;
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
            padding-top: 60px;
        }

        .topbar {
            height: 80px;
            padding: 0 20px;
        }

        .topbar h2 {
            font-size: 23px;
        }

        .customer {
            font-size: 13px;
        }

        .content {
            padding: 20px 15px;
        }

        .detail-card {
            padding: 18px;
        }

        .product-image {
            height: 300px;
        }

        .detail h1 {
            font-size: 30px;
        }

        .price {
            font-size: 27px;
        }
    }
</style>

</head>

<body>

<!-- MENU MOBILE -->
<div class="mobile-bar">
    <div class="mobile-logo">Dwiera</div>

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

            <a href="{{ route('pelanggan') }}">
                <span class="icon">⌂</span>
                <span>Beranda</span>
            </a>

            <a href="{{ route('katalog') }}" class="active">
                <span class="icon">▦</span>
                <span>Katalog</span>
            </a>

            <a href="{{ route('pesanan.pelanggan') }}">
                <span class="icon">♧</span>
                <span>Pesanan Saya</span>
            </a>

            <a href="{{ route('profil') }}">
                <span class="icon">♙</span>
                <span>Profil</span>
            </a>

            <hr>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <span class="icon">↪</span>
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
        <p>BIRTHDAY CAKE &amp; MORE</p>
    </div>

    <nav class="menu">

        <a href="{{ route('pelanggan') }}">
            <span class="icon">⌂</span>
            <span>Beranda</span>
        </a>

        <a href="{{ route('katalog') }}" class="active">
            <span class="icon">▦</span>
            <span>Katalog</span>
        </a>

        <a href="{{ route('pesanan.pelanggan') }}">
            <span class="icon">♧</span>
            <span>Pesanan Saya</span>
        </a>

        <a href="{{ route('profil') }}">
            <span class="icon">♙</span>
            <span>Profil</span>
        </a>

    </nav>

    <div class="logout-area">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="logout-btn">
                <span class="icon">↪</span>
                <span>Logout</span>
            </button>
        </form>
    </div>

</aside>

<!-- MAIN -->
<main class="main">

    <header class="topbar">
        <h2>Detail Produk</h2>

        <div class="customer">
            <div class="customer-icon">♙</div>
            <span>Pelanggan</span>
        </div>
    </header>

    <section class="content">

        <a href="{{ route('katalog') }}" class="back-btn">
            ← Kembali ke Katalog
        </a>

        <div class="detail-card">

            <!-- GAMBAR PRODUK -->
            <div class="product-image">

                @if($produk->gambar)
                    <img
                        src="{{ asset('images/produk/' . $produk->gambar) }}"
                        alt="{{ $produk->nama_kue }}"
                    >
                @else
                    <div class="image-placeholder">🎂</div>
                @endif

            </div>

            <!-- DETAIL PRODUK -->
            <div class="detail">

                <h1>{{ $produk->nama_kue }}</h1>

                <p class="description">
                    {{ $produk->deskripsi }}
                </p>

                <!-- NOTIFIKASI -->
                @if(session('success'))
                    <div class="alert-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-message alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-message alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- INFORMASI PRODUK -->
                <div class="info-box">

                    <div class="info-row">
                        <div class="info-icon">🎂</div>
                        <div class="info-label">Produk</div>
                        <div class="info-value">
                            {{ $produk->nama_kue }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">🧁</div>
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if(strtolower($produk->status) === 'aktif')
                                <span class="status">Tersedia</span>
                            @else
                                {{ $produk->status }}
                            @endif
                        </div>
                    </div>

                </div>

                <!-- FORM TAMBAH KE KERANJANG -->
                <form
                    id="formKeranjang"
                    action="{{ route('keranjang.tambah', ['id' => $produk->id_produk]) }}"
                    method="POST"
                >
                    @csrf

                    <!-- PILIH UKURAN -->
                    <div class="order-field">
                        <label for="pilihan_ukuran">Pilih Ukuran Kue *</label>

                        <select id="pilihan_ukuran" required>
                            <option value="">-- Pilih Ukuran --</option>

                            @foreach($produk->ukuranProduks as $ukuran)
                                <option
                                    value="{{ $ukuran->id_ukuran }}"
                                    data-harga="{{ $ukuran->harga }}"
                                    {{ old('id_ukuran') == $ukuran->id_ukuran ? 'selected' : '' }}
                                >
                                    {{ $ukuran->nama_ukuran }}
                                    — Rp{{ number_format($ukuran->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>

                        <input
                            type="hidden"
                            name="id_ukuran"
                            id="id_ukuran"
                            value="{{ old('id_ukuran') }}"
                        >
                    </div>

                    <!-- HARGA PRODUK -->
                    <div class="price" id="hargaProduk">
                        Pilih ukuran terlebih dahulu
                    </div>

                    <!-- JUMLAH -->
                    <div class="order-field">
                        <label for="jumlah">Jumlah Kue *</label>
                        <input
                            type="number"
                            name="jumlah"
                            id="jumlah"
                            min="1"
                            max="100"
                            value="{{ old('jumlah', 1) }}"
                            required
                        >
                    </div>

                    <!-- TOMBOL KERANJANG -->
                    <button
                        type="submit"
                        class="btn-order"
                        {{ $produk->ukuranProduks->isEmpty() ? 'disabled' : '' }}
                    >
                        🛒 Tambah ke Keranjang
                    </button>
                </form>

                @if($produk->ukuranProduks->isEmpty())
                    <p class="description" style="margin-top:15px;">
                        Maaf, ukuran kue belum tersedia saat ini.
                    </p>
                @endif

            </div>
        </div>

    </section>
</main>

<!-- SCRIPT HARGA DAN VALIDASI -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formKeranjang = document.getElementById('formKeranjang');
    const pilihanUkuran = document.getElementById('pilihan_ukuran');
    const idUkuranKirim = document.getElementById('id_ukuran');
    const hargaProduk = document.getElementById('hargaProduk');
    const jumlah = document.getElementById('jumlah');

    function perbaruiPilihan() {
        const pilihan = pilihanUkuran.options[pilihanUkuran.selectedIndex];

        idUkuranKirim.value = pilihan && pilihan.value ? pilihan.value : '';

        if (!pilihan || !pilihan.value) {
            hargaProduk.textContent = 'Pilih ukuran terlebih dahulu';
            return;
        }

        const harga = Number(pilihan.dataset.harga);

        hargaProduk.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(harga);
    }

    pilihanUkuran.addEventListener('change', perbaruiPilihan);

    formKeranjang.addEventListener('submit', function (event) {
        perbaruiPilihan();

        if (!idUkuranKirim.value) {
            event.preventDefault();
            alert('Silakan pilih ukuran kue terlebih dahulu.');
            pilihanUkuran.focus();
            return;
        }

        if (
            !jumlah.value ||
            Number(jumlah.value) < 1 ||
            Number(jumlah.value) > 100
        ) {
            event.preventDefault();
            alert('Jumlah kue harus antara 1 sampai 100.');
            jumlah.focus();
        }
    });

    perbaruiPilihan();
});
</script>

<!-- SCRIPT MENU TITIK TIGA -->
<script>
    (function () {

        var tombol = document.getElementById('dotsBtn');
        var menu = document.getElementById('dotsMenu');

        if (!tombol || !menu) {
            return;
        }

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
            if (!menu.contains(e.target) && !tombol.contains(e.target)) {
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