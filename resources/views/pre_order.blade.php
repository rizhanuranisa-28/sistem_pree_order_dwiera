<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Order - Dwiera Cake</title>

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
        color: #c9577c;
        text-align: center;
        margin-bottom: 10px;
    }

    .subtitle {
        text-align: center;
        color: #777;
        margin-bottom: 30px;
    }

    .product-box {
        background: #fff0f4;
        padding: 18px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .product-box h3 {
        color: #c9577c;
        margin-bottom: 8px;
    }

    .product-price {
        color: #d46a8c;
        font-weight: bold;
        margin-top: 8px;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #444;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e2d2d7;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
        background: white;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #d46a8c;
    }

    textarea {
        resize: vertical;
        min-height: 90px;
    }

    .quantity {
        width: 120px;
    }

    .is-invalid {
        border-color: #d93a4a;
        background: #fff8f8;
    }

    .field-error {
        margin-top: 6px;
        font-size: 13px;
        line-height: 1.5;
        color: #b42318;
    }

    .hint {
        margin-top: 6px;
        font-size: 12px;
        color: #999;
    }

    .alert-error {
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
    }

    .btn:hover {
        background: #c9577c;
    }

    .btn:disabled {
        background: #c9aab4;
        cursor: not-allowed;
    }

    @media (max-width: 600px) {
        nav {
            padding: 18px 5%;
        }

        .card {
            padding: 22px;
        }

        .container {
            margin: 25px auto;
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

    <a href="{{ route('produk.detail', $produk->id_produk) }}" class="back">
        ← Kembali
    </a>
</nav>

<div class="container">
    <div class="card">

        <h1>Pre-Order Kue</h1>

        <p class="subtitle">
            Isi detail pesanan kamu.
        </p>

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Pesanan belum dapat dilanjutkan:</strong>
                <ul style="padding-left: 20px; margin-top: 6px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $daftarUkuran = $produk->ukuranProduks
                ->where('status', 'aktif')
                ->values();

            $ukuranTerpilih = old('id_ukuran', $daftarUkuran->first()?->id_ukuran);
        @endphp

        <div class="product-box">
            <h3>{{ $produk->nama_kue }}</h3>

            <p>
                Silakan pilih ukuran kue yang kamu inginkan.
            </p>

            <p class="product-price" id="hargaProduk">
                @if ($daftarUkuran->isNotEmpty())
                    Rp{{ number_format($daftarUkuran->first()->harga, 0, ',', '.') }}
                @else
                    Harga belum tersedia
                @endif
            </p>
        </div>

        <form
            action="{{ route('checkout.create', $produk->id_produk) }}"
            method="GET"
        >

            <!-- PILIH UKURAN -->
            <div class="form-group">
                <label for="id_ukuran">
                    Pilih Ukuran Kue
                </label>

                <select
                    name="id_ukuran"
                    id="id_ukuran"
                    class="@error('id_ukuran') is-invalid @enderror"
                    required
                    {{ $daftarUkuran->isEmpty() ? 'disabled' : '' }}
                >
                    @forelse ($daftarUkuran as $ukuran)
                        <option
                            value="{{ $ukuran->id_ukuran }}"
                            data-harga="{{ $ukuran->harga }}"
                            {{ (string) $ukuranTerpilih === (string) $ukuran->id_ukuran ? 'selected' : '' }}
                        >
                            {{ $ukuran->nama_ukuran }} -
                            Rp{{ number_format($ukuran->harga, 0, ',', '.') }}
                        </option>
                    @empty
                        <option value="">
                            Ukuran belum tersedia
                        </option>
                    @endforelse
                </select>

                @error('id_ukuran')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                @if ($daftarUkuran->isEmpty())
                    <div class="field-error">
                        Maaf, produk ini belum memiliki ukuran yang aktif.
                    </div>
                @endif
            </div>

            <!-- JUMLAH -->
            <div class="form-group">
                <label for="jumlah">
                    Jumlah Kue
                </label>

                <input
                    type="number"
                    id="jumlah"
                    name="jumlah"
                    class="quantity @error('jumlah') is-invalid @enderror"
                    min="1"
                    max="100"
                    value="{{ old('jumlah', 1) }}"
                    required
                >

                @error('jumlah')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- TANGGAL PENGAMBILAN -->
            <div class="form-group">
                <label for="tanggal_pengambilan">
                    Tanggal Pengambilan
                </label>

                <input
                    type="date"
                    id="tanggal_pengambilan"
                    name="tanggal_pengambilan"
                    class="@error('tanggal_pengambilan') is-invalid @enderror"
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('tanggal_pengambilan') }}"
                    required
                >

                @error('tanggal_pengambilan')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- JAM PENGAMBILAN -->
            <div class="form-group">
                <label for="jam_pengambilan">
                    Jam Pengambilan
                </label>

                <input
                    type="time"
                    id="jam_pengambilan"
                    name="jam_pengambilan"
                    class="@error('jam_pengambilan') is-invalid @enderror"
                    value="{{ old('jam_pengambilan') }}"
                    required
                >

                @error('jam_pengambilan')
                    <div class="field-error">{{ $message }}</div>
                @else
                    <div class="hint">
                        Jam buka pengambilan 08:00 sampai 20:00,
                        minimal 3 jam dari sekarang.
                    </div>
                @enderror
            </div>

            <!-- CATATAN -->
            <div class="form-group">
                <label for="catatan">
                    Catatan Pesanan
                </label>

                <textarea
                    id="catatan"
                    name="catatan"
                    placeholder="Contoh: Tulisan Happy Birthday Aulia"
                >{{ old('catatan') }}</textarea>
            </div>

            <!-- TOMBOL -->
            <button
                type="submit"
                class="btn"
                {{ $daftarUkuran->isEmpty() ? 'disabled' : '' }}
            >
                Lanjut ke Checkout
            </button>

        </form>

    </div>
</div>

<script>
    const pilihanUkuran = document.getElementById('id_ukuran');
    const hargaProduk = document.getElementById('hargaProduk');

    if (pilihanUkuran) {
        pilihanUkuran.addEventListener('change', function () {
            const opsi = this.options[this.selectedIndex];
            const harga = Number(opsi.dataset.harga || 0);

            hargaProduk.textContent = 'Rp' + harga.toLocaleString('id-ID');
        });
    }
</script>

</body>
</html>
