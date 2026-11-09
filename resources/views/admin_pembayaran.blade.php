<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Dwiera</title>

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
            gap: 15px;
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

        .content-header p {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
        }

        .notice {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            line-height: 1.6;
        }

        .notice.success {
            background: #e3f7ed;
            color: #398363;
        }

        .notice.error {
            background: #fff0d9;
            color: #a65f12;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px;
        }

        th {
            text-align: left;
            padding: 13px 10px;
            font-size: 12px;
            color: #999;
            border-bottom: 1px solid #f0e2e7;
            font-weight: bold;
            white-space: nowrap;
        }

        td {
            padding: 15px 10px;
            font-size: 13px;
            border-bottom: 1px solid #f5eaee;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #fff9fb;
        }

        .order-id {
            color: #d66f8d;
            font-weight: bold;
        }

        .payment {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }

        .payment.cash {
            background: #e3f7ed;
            color: #398363;
        }

        .payment.transfer {
            background: #eee5fa;
            color: #8657ad;
        }

        .amount {
            font-weight: bold;
            color: #444;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status.lunas {
            background: #e3f7ed;
            color: #398363;
        }

        .status.belum {
            background: #fff0d9;
            color: #c27b25;
        }

        .status.dikembalikan {
            background: #fff0f5;
            color: #b64d70;
        }

        .action-form {
            min-width: 145px;
        }

        .cash-input {
            width: 145px;
            padding: 9px;
            margin-bottom: 7px;
            border: 1px solid #e8cbd5;
            border-radius: 7px;
            outline-color: #d66f8d;
        }

        .btn {
            display: inline-block;
            padding: 9px 12px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            text-align: center;
            color: white;
            font-size: 12px;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-cash {
            background: #d66f8d;
        }

        .btn-transfer {
            background: #8657ad;
        }

        .btn-receipt {
            background: #398363;
        }

        .btn-upload {
            background: #d66f8d;
        }

        .btn-upload:hover,
        .btn-cash:hover {
            background: #bd5978;
        }

        .btn-return {
            background: #d66f8d;
            margin-top: 5px;
        }

        .btn-return:hover {
            background: #bd5978;
        }

        .upload-form {
            min-width: 200px;
            max-width: 230px;
            margin-top: 10px;
        }

        .file-input {
            width: 210px;
            max-width: 100%;
            margin-bottom: 8px;
            padding: 7px;
            border: 1px solid #e8cbd5;
            border-radius: 7px;
            background: #fff;
            font-size: 11px;
        }

        .return-form {
            min-width: 180px;
            margin-top: 10px;
        }

        .return-reason {
            width: 180px;
            min-height: 75px;
            padding: 9px;
            margin-bottom: 7px;
            border: 1px solid #e8cbd5;
            border-radius: 7px;
            outline-color: #d66f8d;
            resize: vertical;
            font-size: 12px;
        }

        .returned-reason {
            margin-top: 8px;
            padding: 9px;
            border-radius: 8px;
            background: #fff0f5;
            color: #a64d6b;
            font-size: 12px;
            line-height: 1.5;
            max-width: 190px;
            overflow-wrap: anywhere;
        }

        .small-note {
            display: block;
            margin-top: 6px;
            color: #999;
            max-width: 210px;
            font-size: 11px;
            line-height: 1.5;
        }

        .proof-image {
            display: block;
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #f0e2e7;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #aaa;
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

<div class="mobile-bar">
    <img src="{{ asset('images/logo_dwiera_jpg.jpeg') }}" alt="Logo Dwiera">

    <div class="dots-wrap">
        <button type="button"
                class="dots-btn"
                id="dotsBtn"
                aria-label="Buka menu"
                aria-expanded="false">
            ⋮
        </button>

        <div class="dots-menu" id="dotsMenu">
            <a href="{{ route('admin') }}">
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

            <a href="{{ route('admin.pembayaran') }}" class="active">
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

    <aside class="sidebar">

        <div class="admin-label">ADMIN PANEL</div>

        <nav class="menu">
            <a href="{{ route('admin') }}">
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

            <a href="{{ route('admin.pembayaran') }}" class="active">
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

    <main class="main">

        <div class="topbar">
            <div>
                <h1>Data Pembayaran</h1>
                <p>Kelola pembayaran pesanan kue ulang tahun Dwiera.</p>
            </div>

            <div class="profile">
                <div class="profile-icon">A</div>
                <span>Admin</span>
            </div>
        </div>

        <section class="content-card">

            @if(session('success'))
                <div class="notice success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="notice error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="notice error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="content-header">
                <div>
                    <h2>Daftar Pembayaran</h2>
                    <p>
                        Upload bukti transfer pesanan offline, periksa bukti,
                        atau konfirmasi pembayaran.
                    </p>
                </div>
            </div>

            <div class="table-wrapper">

                @if($pembayaran->count() > 0)

                    <table>
                        <thead>
                            <tr>
                                <th>No. Pembayaran</th>
                                <th>No. Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode</th>
                                <th>Jumlah Bayar</th>
                                <th>Status</th>
                                <th>Bukti Transfer</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pembayaran as $item)

                                @php
                                    $metode = strtolower($item->metode_pembayaran ?? '');
                                    $totalBayar = (float) ($item->total_bayar ?? 0);
                                    $uangDibayarkan = (float) ($item->bayar ?? 0);
                                    $kembalian = (float) ($item->kembalian ?? 0);

                                    $lunas = !empty($item->tanggal_bayar)
                                        && $totalBayar > 0
                                        && $uangDibayarkan >= $totalBayar;

                                    $statusBukti = $item->status_bukti ?? 'menunggu';
                                    $alasanPengembalian = $item->alasan_pengembalian;

                                    /*
                                     * Pesanan offline dibuat tanpa id_users.
                                     */
                                    $pesanan = $item->pesanan;

                                    $offline = $pesanan
                                        && $pesanan->id_users === null;

                                    /*
                                     * Nama pelanggan online diambil dari user.
                                     * Untuk offline, ambil dari relasi customer jika tersedia.
                                     */
                                    $namaPelanggan = $pesanan->user->nama_users
                                        ?? $pesanan->customer->nama_customer
                                        ?? $pesanan->customer->nama
                                        ?? '-';

                                    $pathBukti = $item->bukti_pembayaran;
                                    $urlBukti = null;

                                    if (!empty($pathBukti)) {
                                        $pathBukti = str_replace('\\', '/', $pathBukti);
                                        $urlBukti = str_starts_with($pathBukti, 'bukti/')
                                            ? asset('storage/' . $pathBukti)
                                            : asset('images/bukti/' . basename($pathBukti));
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        <span class="order-id">
                                            #PAY{{ str_pad($item->id_pembayaran, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>

                                    <td>
                                        #DW{{ str_pad($item->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td>
                                        {{ $namaPelanggan }}

                                        @if($offline)
                                            <br>
                                            <small style="color:#c66b88;">Pesanan offline</small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->tanggal_bayar
                                            ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i')
                                            : '-'
                                        }}
                                    </td>

                                    <td>
                                        @if($metode === 'cash')
                                            <span class="payment cash">Cash</span>
                                        @elseif($metode === 'transfer')
                                            <span class="payment transfer">Transfer</span>
                                        @elseif($metode === 'qris')
                                            <span class="payment transfer">QRIS</span>
                                        @else
                                            <span class="payment">Tidak valid</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="amount">
                                            Rp {{ number_format($lunas ? $uangDibayarkan : $totalBayar, 0, ',', '.') }}
                                        </span>

                                        @if($lunas && $metode === 'cash' && $kembalian > 0)
                                            <br>
                                            <small style="display:inline-block;margin-top:5px;color:#398363;">
                                                Kembalian:
                                                Rp {{ number_format($kembalian, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        @if($lunas)
                                            <span class="status lunas">Lunas</span>

                                        @elseif($metode === 'transfer' && $statusBukti === 'dikembalikan')
                                            <span class="status dikembalikan">Bukti Dikembalikan</span>

                                        @elseif($metode === 'transfer' && $statusBukti === 'diterima')
                                            <span class="status lunas">Bukti Diterima</span>

                                        @elseif($metode === 'transfer' && empty($item->bukti_pembayaran))
                                            <span class="status belum">Belum Ada Bukti</span>

                                        @else
                                            <span class="status belum">Belum Lunas</span>
                                        @endif

                                        @if(
                                            $metode === 'transfer'
                                            && $statusBukti === 'dikembalikan'
                                            && $alasanPengembalian
                                        )
                                            <div class="returned-reason">
                                                <strong>Alasan:</strong><br>
                                                {{ $alasanPengembalian }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if($urlBukti)
                                            <a href="{{ $urlBukti }}"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <img src="{{ $urlBukti }}"
                                                     alt="Bukti transfer"
                                                     class="proof-image">
                                            </a>
                                        @elseif($metode === 'transfer')
                                            <span style="color:#c27b25;font-size:12px;">
                                                Belum ada bukti
                                            </span>
                                        @else
                                            <span style="color:#999;">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($lunas)

                                            <a href="{{ route('struk.admin', $item->id_pesanan) }}"
                                               class="btn btn-receipt">
                                                Lihat Struk
                                            </a>

                                        @elseif($metode === 'cash')

                                            <form class="action-form"
                                                  action="{{ route('admin.pembayaran.proses', $item->id_pembayaran) }}"
                                                  method="POST">
                                                @csrf

                                                <input
                                                    class="cash-input"
                                                    type="number"
                                                    name="jumlah_bayar"
                                                    min="{{ ceil($totalBayar) }}"
                                                    step="1"
                                                    placeholder="Uang diterima (Rp)"
                                                    aria-label="Uang diterima untuk pembayaran cash"
                                                    required
                                                >

                                                <button type="submit" class="btn btn-cash">
                                                    Terima Cash
                                                </button>
                                            </form>

                                        @elseif($metode === 'transfer')

                                            {{-- Upload bukti untuk pesanan offline yang belum lunas --}}
                                            @if($offline && !$lunas)

                                                <form class="upload-form"
                                                      action="{{ route('admin.pembayaran.uploadBukti', $item->id_pembayaran) }}"
                                                      method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf

                                                    <label style="display:block;font-size:12px;margin-bottom:7px;color:#666;">
                                                        {{ empty($item->bukti_pembayaran)
                                                            ? 'Pilih bukti transfer:'
                                                            : 'Ganti bukti transfer:'
                                                        }}
                                                    </label>

                                                    <input
                                                        class="file-input"
                                                        type="file"
                                                        name="bukti_pembayaran"
                                                        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                                        required
                                                    >

                                                    <button type="submit" class="btn btn-upload">
                                                        Upload Bukti
                                                    </button>
                                                </form>

                                                <small class="small-note">
                                                    Format JPG, JPEG, atau PNG. Maksimal 5 MB.
                                                </small>

                                            @endif

                                            @if(
                                                !empty($item->bukti_pembayaran)
                                                && $statusBukti === 'menunggu'
                                            )

                                                <form class="action-form"
                                                      style="margin-top:10px;"
                                                      action="{{ route('admin.pembayaran.proses', $item->id_pembayaran) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Pastikan dana transfer benar-benar sudah masuk. Konfirmasi pembayaran ini?')">
                                                    @csrf

                                                    <button type="submit" class="btn btn-transfer">
                                                        Konfirmasi Transfer
                                                    </button>
                                                </form>

                                                <form class="return-form"
                                                      action="{{ route('admin.pembayaran.kembalikan', $item->id_pembayaran) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin mengembalikan bukti transfer ini?')">
                                                    @csrf

                                                    <textarea
                                                        class="return-reason"
                                                        name="alasan_pengembalian"
                                                        placeholder="Tuliskan alasan pengembalian..."
                                                        minlength="5"
                                                        maxlength="1000"
                                                        required
                                                    ></textarea>

                                                    <button type="submit" class="btn btn-return">
                                                        Kembalikan Bukti
                                                    </button>
                                                </form>

                                                <small class="small-note">
                                                    Pastikan dana sudah masuk sebelum mengonfirmasi pembayaran.
                                                </small>

                                            @elseif($statusBukti === 'dikembalikan')

                                                <small class="small-note" style="color:#a64d6b;">
                                                    Bukti sebelumnya dikembalikan.
                                                    @if($offline)
                                                        Admin dapat mengunggah bukti yang benar setelah memeriksanya.
                                                    @else
                                                        Menunggu pelanggan mengunggah bukti baru.
                                                    @endif
                                                </small>

                                            @elseif(empty($item->bukti_pembayaran))

                                                @if(!$offline)
                                                    <small class="small-note" style="color:#c27b25;">
                                                        Menunggu bukti transfer pelanggan.
                                                    </small>
                                                @endif

                                            @else

                                                <span style="color:#398363;font-size:12px;">
                                                    Bukti sudah diterima.
                                                </span>

                                            @endif

                                        @else

                                            <span style="color:#999;">Metode tidak valid</span>

                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                @else

                    <div class="empty">
                        Belum ada data pembayaran.
                    </div>

                @endif

            </div>
        </section>
    </main>

</div>

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

            tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
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