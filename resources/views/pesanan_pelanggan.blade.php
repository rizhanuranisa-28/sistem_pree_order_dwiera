<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pesanan Saya - Dwiera Cake</title>

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

    .back {
        color: #d46a8c;
        text-decoration: none;
        font-size: 14px;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .header {
        margin-bottom: 25px;
    }

    .header h1 {
        color: #c9577c;
        margin-bottom: 8px;
    }

    .header p {
        color: #777;
    }

    .order-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .order-number {
        font-weight: bold;
        color: #c9577c;
    }

    .status {
        background: #fff0f4;
        color: #c9577c;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }

    .order-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 25px;
        margin-bottom: 20px;
    }

    .info-item {
        color: #666;
        line-height: 1.5;
    }

    .info-item strong {
        display: block;
        color: #333;
    }

    .product-list {
        background: #fff6f8;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .product-list h3,
    .order-note h3,
    .proof-box h3,
    .history-box h3 {
        color: #c9577c;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .product-item {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 8px 0;
        border-bottom: 1px solid #ead8de;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .order-note {
        background: #fff6f8;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .order-note p {
        font-size: 14px;
        color: #555;
        line-height: 1.6;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .proof-box {
        background: #fff6f8;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .proof-img {
        display: block;
        max-width: 180px;
        max-height: 180px;
        border-radius: 10px;
        border: 1px solid #ead8de;
        margin-bottom: 12px;
        object-fit: cover;
    }

    .proof-note {
        font-size: 13px;
        color: #876774;
        margin-bottom: 10px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .proof-form input[type="file"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #e8c5d2;
        border-radius: 8px;
        background: white;
        font-size: 13px;
    }

    .total {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding-top: 15px;
        border-top: 1px solid #ead8de;
        font-size: 18px;
        font-weight: bold;
        color: #d46a8c;
    }

    .order-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        display: inline-block;
        padding: 11px 18px;
        background: #d46a8c;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn:hover {
        background: #c9577c;
    }

    .btn-edit {
        background: #d46a8c;
    }

    .btn-edit:hover {
        background: #c9577c;
    }

    .empty {
        background: white;
        padding: 40px 25px;
        border-radius: 15px;
        text-align: center;
        color: #777;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    .empty h3 {
        margin-bottom: 10px;
        color: #c9577c;
    }

    .alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .alert-success {
        color: #216b43;
        background: #e8f8ee;
        border: 1px solid #b9e7c9;
    }

    .alert-error {
        color: #a52a2a;
        background: #fff0f0;
        border: 1px solid #f1c5c5;
    }

    .edit-note {
        margin-top: 12px;
        font-size: 12px;
        color: #876774;
    }

    .proof-status {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 12px;
    }

    .proof-status.waiting {
        background: #fff0d9;
        color: #a66b18;
    }

    .proof-status.returned {
        background: #ffe5ed;
        color: #b64d70;
    }

    .proof-status.accepted {
        background: #e3f7ed;
        color: #398363;
    }

    .returned-reason {
        background: #fff0f5;
        border: 1px solid #f0cbd8;
        color: #a64d6b;
        padding: 13px;
        border-radius: 9px;
        margin: 5px 0 15px;
        font-size: 13px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .history-box {
        background: #fff;
        border: 1px solid #f0dce4;
        padding: 15px;
        border-radius: 10px;
        margin-top: 18px;
    }

    .history-item {
        border-top: 1px solid #f0dce4;
        padding: 13px 0;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .history-item:first-of-type {
        border-top: none;
    }

    .history-item:last-child {
        padding-bottom: 0;
    }

    .history-thumb {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ead8de;
        flex-shrink: 0;
    }

    .history-info {
        min-width: 0;
        font-size: 12px;
        color: #777;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .history-info strong {
        color: #444;
    }

    .history-link {
        color: #c9577c;
        text-decoration: none;
        font-weight: bold;
    }

    .history-reason {
        margin-top: 5px;
        color: #a64d6b;
    }

    @media (max-width: 600px) {
        nav {
            padding: 18px 5%;
        }

        .logo {
            font-size: 21px;
        }

        .container {
            margin: 30px auto;
        }

        .order-card {
            padding: 20px;
        }

        .order-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .order-info {
            grid-template-columns: 1fr;
        }

        .product-item {
            flex-direction: column;
            gap: 4px;
        }

        .order-actions {
            flex-direction: column;
        }

        .order-actions .btn {
            text-align: center;
        }

        .total {
            font-size: 16px;
        }
    }
</style>


</head>

<body>


<nav>
    <div class="logo">
        Dwiera Cake
    </div>

    <a href="{{ route('pelanggan') }}" class="back">
        ← Dashboard
    </a>
</nav>

<div class="container">

    <div class="header">
        <h1>Pesanan Saya</h1>
        <p>Lihat pesanan dan status pemesanan kue kamu.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="status">
            💗 {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" role="alert">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error" role="alert">
            @foreach($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @forelse($pesanan as $order)

        <div class="order-card">

            <div class="order-header">
                <div class="order-number">
                    Pesanan #{{ $order->id_pesanan }}
                </div>

                <div class="status">
                    {{ ucfirst(str_replace('_', ' ', $order->status_pesanan)) }}
                </div>
            </div>

            <div class="order-info">

                <div class="info-item">
                    Tanggal Pesan
                    <strong>
                        {{ $order->tanggal_pesan
                            ? \Carbon\Carbon::parse($order->tanggal_pesan)->format('d-m-Y H:i')
                            : '-' }}
                    </strong>
                </div>

                <div class="info-item">
                    Tanggal Pengambilan
                    <strong>
                        {{ $order->tanggal_pengambilan
                            ? \Carbon\Carbon::parse($order->tanggal_pengambilan)->format('d-m-Y')
                            : '-' }}
                    </strong>
                </div>

                @if($order->jadwalPengambilan)
                    <div class="info-item">
                        Jam Pengambilan
                        <strong>
                            {{ $order->jadwalPengambilan->jam_pengambilan }}
                        </strong>
                    </div>
                @endif

                @if($order->pembayaran)
                    <div class="info-item">
                        Metode Pembayaran
                        <strong>
                            {{ ucfirst($order->pembayaran->metode_pembayaran) }}
                        </strong>
                    </div>
                @endif

            </div>

            <div class="product-list">
                <h3>Detail Kue</h3>

                @forelse($order->detailPesanans as $detail)
                    <div class="product-item">
                        <span>
                            {{ $detail->produk->nama_kue ?? 'Produk' }}
                            × {{ $detail->jumlah }}
                        </span>

                        <strong>
                            Rp{{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}
                        </strong>
                    </div>
                @empty
                    <p>Detail kue belum tersedia.</p>
                @endforelse
            </div>

            @if(!empty($order->catatan))
                <div class="order-note">
                    <h3>Catatan Pesanan</h3>
                    <p>{{ $order->catatan }}</p>
                </div>
            @endif

            <div class="total">
                <span>Total</span>
                <span>
                    Rp{{ number_format($order->total_harga ?? 0, 0, ',', '.') }}
                </span>
            </div>

            {{-- Informasi dan riwayat bukti transfer --}}
            @if(
                $order->pembayaran
                && strtolower((string) $order->pembayaran->metode_pembayaran) === 'transfer'
            )

                @php
                    $pembayaran = $order->pembayaran;
                    $sudahLunas = !empty($pembayaran->tanggal_bayar);
                    $bukti = $pembayaran->bukti_pembayaran;
                    $statusBukti = $pembayaran->status_bukti ?? 'menunggu';
                    $alasanPengembalian = $pembayaran->alasan_pengembalian;
                    $riwayatBukti = $pembayaran->riwayatBukti ?? collect();
                    $buktiUrl = static function ($path) {
                        $path = str_replace('\\', '/', (string) $path);

                        return str_starts_with($path, 'bukti/')
                            ? asset('storage/' . $path)
                            : asset('images/bukti/' . basename($path));
                    };
                @endphp

                <div class="proof-box" style="margin-top: 20px;">

                    <h3>Bukti Transfer</h3>

                    {{-- Status bukti --}}
                    @if($sudahLunas)
                        <div class="proof-status accepted">
                            Pembayaran Lunas
                        </div>
                    @elseif($statusBukti === 'dikembalikan')
                        <div class="proof-status returned">
                            Bukti Dikembalikan Admin
                        </div>
                    @elseif($statusBukti === 'diterima')
                        <div class="proof-status accepted">
                            Bukti Diterima
                        </div>
                    @elseif(empty($bukti))
                        <div class="proof-status waiting">
                            Belum Ada Bukti
                        </div>
                    @else
                        <div class="proof-status waiting">
                            Menunggu Pemeriksaan Admin
                        </div>
                    @endif

                    {{-- Bukti terbaru --}}
                    @if($bukti)
                        <p class="proof-note">
                            Bukti transfer terbaru:
                        </p>

                        <a href="{{ $buktiUrl($bukti) }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            <img src="{{ $buktiUrl($bukti) }}"
                                 alt="Bukti transfer terbaru"
                                 class="proof-img">
                        </a>
                    @else
                        <p class="proof-note">
                            Belum ada bukti transfer yang diunggah.
                        </p>
                    @endif

                    {{-- Alasan admin mengembalikan bukti --}}
                    @if(!$sudahLunas && $statusBukti === 'dikembalikan')
                        <div class="returned-reason">
                            <strong>Alasan pengembalian dari admin:</strong>
                            <br>
                            {{ $alasanPengembalian ?: 'Silakan hubungi admin untuk mengetahui alasan pengembalian.' }}
                        </div>

                        <p class="proof-note">
                            Silakan periksa alasan di atas, perbaiki bukti transfer kamu,
                            lalu unggah kembali bukti yang benar.
                        </p>
                    @endif

                    {{-- Form upload atau upload ulang --}}
                    @if(!$sudahLunas)

                        <form class="proof-form"
                              action="{{ route('pesanan.bukti', $order->id_pesanan) }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf

                            <input
                                type="file"
                                name="bukti"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                            <button type="submit" class="btn">
                                @if($statusBukti === 'dikembalikan')
                                    Unggah Ulang Bukti
                                @elseif($bukti)
                                    Ganti Bukti Transfer
                                @else
                                    Unggah Bukti Transfer
                                @endif
                            </button>

                        </form>

                        <p class="proof-note" style="margin-top: 8px;">
                            Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.
                            Setelah diunggah, bukti akan diperiksa kembali oleh admin.
                        </p>

                    @else

                        <p class="proof-note">
                            Pembayaran sudah dikonfirmasi admin. Bukti tidak dapat diubah lagi.
                        </p>

                    @endif

                    {{-- Riwayat semua bukti --}}
                    @if($riwayatBukti->isNotEmpty())

                        <div class="history-box">

                            <h3>Riwayat Bukti Transfer</h3>

                            <p class="proof-note">
                                Bukti yang pernah diunggah tetap tersimpan di riwayat ini.
                            </p>

                            @foreach($riwayatBukti->sortByDesc('id_riwayat') as $riwayat)

                                <div class="history-item">

                                    <a href="{{ $buktiUrl($riwayat->nama_file) }}"
                                       target="_blank"
                                       rel="noopener noreferrer">

                                        <img
                                            src="{{ $buktiUrl($riwayat->nama_file) }}"
                                            alt="Riwayat bukti transfer"
                                            class="history-thumb"
                                            loading="lazy"
                                        >

                                    </a>

                                    <div class="history-info">

                                        <strong>
                                            Bukti #{{ $riwayat->id_riwayat }}
                                        </strong>

                                        <br>

                                        <span>
                                            {{ $riwayat->tanggal_upload
                                                ? \Carbon\Carbon::parse($riwayat->tanggal_upload)->format('d-m-Y H:i')
                                                : '-' }}
                                        </span>

                                        <br>

                                        @if($riwayat->status === 'dikembalikan')
                                            <span class="proof-status returned">
                                                Dikembalikan
                                            </span>
                                        @elseif($riwayat->status === 'diterima')
                                            <span class="proof-status accepted">
                                                Diterima
                                            </span>
                                        @else
                                            <span class="proof-status waiting">
                                                Menunggu Pemeriksaan
                                            </span>
                                        @endif

                                        <br>

                                        <a
                                            href="{{ $buktiUrl($riwayat->nama_file) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="history-link"
                                        >
                                            Lihat Bukti
                                        </a>

                                        @if($riwayat->status === 'dikembalikan' && $riwayat->alasan_pengembalian)
                                            <div class="history-reason">
                                                <strong>Alasan:</strong>
                                                {{ $riwayat->alasan_pengembalian }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endif

            {{-- Tombol edit pesanan --}}
            @if($order->status_pesanan === 'menunggu')

                <div class="order-actions">

                    <a
                        href="{{ route('pesanan.edit', $order->id_pesanan) }}"
                        class="btn btn-edit"
                    >
                        ✏️ Edit Pesanan
                    </a>

                </div>

                <p class="edit-note">
                    Pesanan hanya bisa diedit sebelum mulai diproses.
                </p>

            @endif

        </div>

    @empty

        <div class="empty">
            <h3>Belum Ada Pesanan</h3>

            <p>
                Kamu belum memiliki pesanan kue.
            </p>

            <a href="{{ route('katalog') }}" class="btn">
                Lihat Katalog
            </a>
        </div>

    @endforelse

</div>

</body>
</html>
