<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Keranjang - Dwiera</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; }

    body {
        margin: 0;
        padding: 25px 15px;
        font-family: 'Poppins', sans-serif;
        background: #fff5f8;
        color: #50343f;
    }

    .container { max-width: 850px; width: 100%; margin: auto; }

    .header { margin-bottom: 25px; }

    .header a {
        color: #c44f7b;
        text-decoration: none;
        font-weight: 600;
    }

    h1 { margin: 15px 0 5px; color: #c44f7b; font-size: 28px; }
    h2 { margin-top: 0; font-size: 18px; color: #a83f69; }
    .subtitle, .item-detail, .hint, .notice { color: #876d78; }

    .subtitle, .item-detail { font-size: 13px; }

    .card {
        background: white;
        padding: 24px;
        margin-bottom: 20px;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(170,80,115,.08);
    }

    .item {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f5e4eb;
    }

    .item-name { font-weight: 600; }
    .item-detail { margin-top: 5px; }

    .size-label {
        display: inline-block;
        margin-top: 7px;
        padding: 4px 10px;
        border-radius: 20px;
        background: #fbe2ec;
        color: #a83f69;
        font-size: 12px;
        font-weight: 600;
    }

    .subtotal {
        text-align: right;
        font-weight: 600;
        white-space: nowrap;
    }

    .total {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding-top: 18px;
        margin-top: 10px;
        border-top: 2px dashed #efcbd9;
        font-size: 18px;
        font-weight: 700;
        color: #bd4775;
    }

    .form-group { margin-bottom: 18px; }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    input, select, textarea {
        display: block;
        width: 100%;
        min-height: 46px;
        padding: 11px 12px;
        border: 1px solid #e8c6d5;
        border-radius: 10px;
        background: white;
        font-family: inherit;
        font-size: 14px;
        color: #50343f;
    }

    textarea {
        min-height: 110px;
        resize: vertical;
        line-height: 1.6;
    }

    .is-invalid {
        border-color: #d93a4a;
        background: #fff8f8;
    }

    .field-error {
        margin-top: 6px;
        font-size: 12px;
        line-height: 1.5;
        color: #b42318;
        font-weight: 500;
    }

    .hint { margin-top: 6px; font-size: 12px; }

    .alert {
        padding: 13px 15px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 13px;
    }

    .alert-error { background: #ffe8e8; color: #a12d36; }
    .alert-success { background: #e7f8ed; color: #236b3b; }
    .error-list { margin: 5px 0 0; padding-left: 20px; }

    .btn {
        display: block;
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 14px 18px;
        background: #c44f7b;
        color: white;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
    }

    .btn:hover { background: #a83f69; }
    .notice { font-size: 12px; line-height: 1.7; margin-top: 14px; }

    @media (max-width: 480px) {
        h1 { font-size: 23px; }
        .card { padding: 18px; }
        .item { align-items: flex-start; }
        .subtotal { font-size: 13px; }
    }
</style>

</head>
<body>

<div class="container">

<header class="header">
    <a href="{{ route('keranjang.index') }}">← Kembali ke Keranjang</a>
    <h1>Checkout Pesanan</h1>
    <p class="subtitle">
        Periksa pesanan dan tentukan jadwal pengambilan kue kamu.
    </p>
</header>

@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- RINGKASAN PESANAN -->
<section class="card">
    <h2>Ringkasan Pesanan</h2>

    @foreach ($items as $item)
        <div class="item">
            <div>
                <div class="item-name">
                    {{ $item['produk']->nama_kue }}
                </div>

                <div class="size-label">
                    Ukuran: {{ $item['ukuran']->nama_ukuran }}
                </div>

                <div class="item-detail">
                    {{ $item['jumlah'] }} ×
                    Rp{{ number_format($item['harga'], 0, ',', '.') }}
                </div>
            </div>

            <div class="subtotal">
                Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
            </div>
        </div>
    @endforeach

    <div class="total">
        <span>Total Tagihan</span>
        <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
    </div>
</section>

<!-- FORM CHECKOUT -->
<form action="{{ route('checkout.keranjang.proses') }}" method="POST">
    @csrf

    <!-- JADWAL PENGAMBILAN -->
    <section class="card">
        <h2>Jadwal Pengambilan</h2>

        <div class="form-group">
            <label for="tanggal_pengambilan">Tanggal Pengambilan</label>

            <input
                type="date"
                id="tanggal_pengambilan"
                name="tanggal_pengambilan"
                value="{{ old('tanggal_pengambilan') }}"
                min="{{ date('Y-m-d') }}"
                class="@error('tanggal_pengambilan') is-invalid @enderror"
                required
            >

            @error('tanggal_pengambilan')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="jam_pengambilan">Jam Pengambilan</label>

            <input
                type="time"
                id="jam_pengambilan"
                name="jam_pengambilan"
                value="{{ old('jam_pengambilan') }}"
                class="@error('jam_pengambilan') is-invalid @enderror"
                required
            >

            @error('jam_pengambilan')
                <div class="field-error">{{ $message }}</div>
            @else
                <div class="hint">
                    Pilih jam pengambilan sesuai kesepakatan dengan toko.
                </div>
            @enderror
        </div>
    </section>

    <!-- CATATAN PESANAN -->
    <section class="card">
        <h2>Catatan Pesanan</h2>

        <div class="form-group">
            <label for="catatan">Catatan Pesanan</label>

            <textarea
                id="catatan"
                name="catatan"
                rows="4"
                maxlength="500"
                placeholder="Contoh: Birthday Cake tulisan Happy Birthday Aulia, Cherry Cake tanpa lilin"
            >{{ old('catatan') }}</textarea>

            <div class="hint">
                Opsional. Tuliskan catatan untuk semua kue dalam keranjang (maksimal 500 karakter).
            </div>
        </div>
    </section>

    <!-- METODE PEMBAYARAN -->
    <section class="card">
        <h2>Metode Pembayaran</h2>

        <div class="form-group">
            <label for="metode">Pilih Metode Pembayaran</label>

            <select
                id="metode"
                name="metode"
                class="@error('metode') is-invalid @enderror"
                required
            >
                <option value="">-- Pilih Pembayaran --</option>

                <option value="cash" {{ old('metode') === 'cash' ? 'selected' : '' }}>
                    Cash / Tunai
                </option>

                <option value="transfer" {{ old('metode') === 'transfer' ? 'selected' : '' }}>
                    Transfer Bank
                </option>
            </select>

            @error('metode')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <div class="hint">
                Pembayaran akan diproses sesuai metode yang dipilih.
            </div>
        </div>
    </section>

    <button type="submit" class="btn">
        Buat Pesanan
    </button>

    <p class="notice">
        Satu pesanan mencakup seluruh jenis kue dalam keranjang.
        Pastikan tanggal, jam pengambilan, dan metode pembayaran sudah benar.
    </p>
</form>

</div>

</body>
</html>
