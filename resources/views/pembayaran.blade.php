<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Dwiera Cake</title>

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

    .back:hover {
        color: #c9577c;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    h1 {
        text-align: center;
        color: #c9577c;
        margin-bottom: 10px;
    }

    .subtitle {
        text-align: center;
        color: #777;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .summary {
        background: #fff0f4;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .summary h3 {
        color: #c9577c;
        margin-bottom: 15px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 12px;
    }

    .row span {
        color: #666;
    }

    .row strong {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .total {
        border-top: 1px solid #e5cfd6;
        margin-top: 15px;
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        gap: 15px;
        font-size: 20px;
        font-weight: bold;
        color: #d46a8c;
    }

    .payment-box {
        padding: 20px;
        border: 1px solid #ead8de;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .payment-box h3 {
        color: #c9577c;
        margin-bottom: 10px;
    }

    .payment-info {
        background: #fff6f8;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        color: #555;
        line-height: 1.8;
    }

    .error-message {
        background: #fff0f0;
        border: 1px solid #f1b7b7;
        color: #a33333;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
    }

    .btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 9px;
        background: #d46a8c;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn:hover {
        background: #c9577c;
    }

    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .note {
        margin-top: 15px;
        font-size: 12px;
        color: #999;
        text-align: center;
        line-height: 1.6;
    }

    @media (max-width: 600px) {
        nav {
            padding: 18px 5%;
        }

        .logo {
            font-size: 22px;
        }

        .card {
            padding: 22px;
        }

        .row {
            flex-direction: column;
            gap: 4px;
        }

        .row strong {
            text-align: left;
        }

        .total {
            font-size: 18px;
        }
    }
</style>
```

</head>

<body>

```
<nav>
    <div class="logo">Dwiera Cake</div>

    <a
        href="{{ route('checkout.create', $produk->id_produk) }}?id_ukuran={{ $ukuran->id_ukuran }}&jumlah={{ $jumlah }}&tanggal_pengambilan={{ urlencode($tanggalPengambilan ?? '') }}&jam_pengambilan={{ urlencode($jamPengambilan ?? '') }}&catatan={{ urlencode($catatan ?? '') }}"
        class="back"
    >
        ← Kembali
    </a>
</nav>

<div class="container">
    <div class="card">

        <h1>Pembayaran</h1>

        <p class="subtitle">
            Periksa kembali detail pesanan kamu sebelum mengonfirmasi.
        </p>

        @if ($errors->any())
            <div class="error-message">
                <strong>Pesanan belum dapat dikonfirmasi.</strong>
                <ul style="padding-left: 20px; margin-top: 8px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="summary">

            <h3>Ringkasan Pesanan</h3>

            <div class="row">
                <span>Nama Kue</span>
                <strong>{{ $produk->nama_kue }}</strong>
            </div>

            <div class="row">
                <span>Ukuran</span>
                <strong>{{ $ukuran->nama_ukuran }}</strong>
            </div>

            <div class="row">
                <span>Harga Satuan</span>
                <strong>
                    Rp{{ number_format($harga, 0, ',', '.') }}
                </strong>
            </div>

            <div class="row">
                <span>Jumlah</span>
                <strong>{{ $jumlah }} kue</strong>
            </div>

            <div class="row">
                <span>Tanggal Pengambilan</span>
                <strong>
                    {{ $tanggalPengambilan
                        ? \Carbon\Carbon::parse($tanggalPengambilan)->format('d/m/Y')
                        : '-' }}
                </strong>
            </div>

            <div class="row">
                <span>Jam Pengambilan</span>
                <strong>{{ $jamPengambilan ?: '-' }}</strong>
            </div>

            <div class="row">
                <span>Metode Pembayaran</span>
                <strong>{{ strtoupper($metode) }}</strong>
            </div>

            <div class="total">
                <span>Total Tagihan</span>
                <span>
                    Rp{{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

        </div>

        <div class="payment-box">

            <h3>Instruksi Pembayaran</h3>

            @if ($metode === 'cash')

                <div class="payment-info">
                    Pembayaran dilakukan secara <strong>cash</strong>
                    saat kamu mengambil kue di toko Dwiera.
                    Admin akan memeriksa pesanan sebelum pesanan diproses.
                </div>

            @elseif ($metode === 'transfer')

                <div class="payment-info">
                    Silakan lakukan pembayaran melalui transfer ke rekening resmi Dwiera berikut.

                    <br><br>
                    🏦 Bank: BCA
                    <br>
                    👤 Atas Nama: Dewi sri
                    <br>
                    💳 Nomor Rekening: 3460788616

                    <br><br>
                    Setelah melakukan pembayaran, silakan simpan bukti transfer. Admin akan memeriksa dan memverifikasi pembayaran kamu.
                </div>

            @else

                <div class="payment-info">
                    Metode pembayaran belum dikenali.
                    Silakan kembali ke halaman checkout dan pilih metode
                    pembayaran yang tersedia.
                </div>

            @endif

        </div>

        <form
            action="{{ route('pembayaran.konfirmasi', $produk->id_produk) }}"
            method="POST"
            id="formKonfirmasi"
        >
            @csrf

            <input
                type="hidden"
                name="id_ukuran"
                value="{{ $ukuran->id_ukuran }}"
            >

            <input type="hidden" name="jumlah" value="{{ $jumlah }}">

            <input
                type="hidden"
                name="tanggal_pengambilan"
                value="{{ $tanggalPengambilan }}"
            >

            <input
                type="hidden"
                name="jam_pengambilan"
                value="{{ $jamPengambilan }}"
            >

            <input
                type="hidden"
                name="catatan"
                value="{{ $catatan }}"
            >

            <input
                type="hidden"
                name="metode"
                value="{{ $metode }}"
            >

            <button type="submit" class="btn" id="tombolKonfirmasi">
                Konfirmasi Pesanan
            </button>
        </form>

        <p class="note">
            Pesanan akan diteruskan untuk diperiksa admin.
            Pastikan semua informasi sudah benar.
        </p>

    </div>
</div>

<script>
    document.getElementById('formKonfirmasi').addEventListener('submit', function () {
        const tombol = document.getElementById('tombolKonfirmasi');

        tombol.disabled = true;
        tombol.textContent = 'Memproses Pesanan...';
    });
</script>

</body>
</html>
