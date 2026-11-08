<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Checkout - Dwiera Cake</title>

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
    }

    .order-box {
        background: #fff0f4;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .order-box h3 {
        color: #c9577c;
        margin-bottom: 15px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 12px;
    }

    .row span:first-child {
        color: #666;
    }

    .row strong {
        text-align: right;
    }

    .note {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e5cfd6;
    }

    .total {
        border-top: 1px solid #e5cfd6;
        margin-top: 15px;
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        font-size: 20px;
        font-weight: bold;
        color: #d46a8c;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    input,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #e0d0d5;
        border-radius: 8px;
        font-size: 15px;
        background: white;
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
    }

    .btn:hover {
        background: #c9577c;
    }

    @media (max-width: 600px) {
        nav {
            padding: 18px 5%;
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
    }
</style>
```

</head>

<body>

```
<nav>
    <div class="logo">
        Dwiera Cake
    </div>

    <a href="{{ route('preorder.create', $produk->id_produk) }}" class="back">
        ← Kembali
    </a>
</nav>

<div class="container">

    <div class="card">

        <h1>Checkout</h1>

        <p class="subtitle">
            Periksa kembali detail pesanan kamu.
        </p>

        <div class="order-box">

            <h3>Detail Pesanan</h3>

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
                <strong>{{ $tanggalPengambilan }}</strong>
            </div>

            <div class="row">
                <span>Jam Pengambilan</span>
                <strong>{{ $jamPengambilan }}</strong>
            </div>

            @if($catatan)
                <div class="note">
                    <strong>Catatan Pesanan</strong>

                    <p style="margin-top: 8px; color: #666;">
                        {{ $catatan }}
                    </p>
                </div>
            @endif

            <div class="total">
                <span>Total</span>

                <span>
                    Rp{{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

        </div>

        <form
            action="{{ route('pembayaran.create', $produk->id_produk) }}"
            method="GET"
        >

            <input
                type="hidden"
                name="id_ukuran"
                value="{{ $ukuran->id_ukuran }}"
            >

            <input
                type="hidden"
                name="jumlah"
                value="{{ $jumlah }}"
            >

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

            <div class="form-group">

                <label for="metode">
                    Metode Pembayaran
                </label>

                <select id="metode" name="metode" required>

                    <option value="">
                        -- Pilih Pembayaran --
                    </option>

                    <option value="cash">
                        Cash
                    </option>

                    <option value="transfer">
                        Transfer
                    </option>

                </select>

            </div>

            <button type="submit" class="btn">
                Lanjut ke Pembayaran
            </button>

        </form>

    </div>

</div>

</body>
</html>
