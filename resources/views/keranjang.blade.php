<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - Dwiera</title>

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
    h1 { color: #c44f7b; font-size: 28px; }
    h2 { color: #a83f69; font-size: 19px; }
    .subtitle { color: #876d78; font-size: 14px; }

    .card {
        background: white;
        padding: 24px;
        margin: 20px 0;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(170,80,115,.08);
    }

    .item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 0;
        border-bottom: 1px solid #f5e4eb;
    }

    .item-name { font-weight: 600; }

    .item-detail {
        color: #876d78;
        font-size: 13px;
        margin-top: 5px;
    }

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

    .subtotal { font-weight: 700; white-space: nowrap; }

    .actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .qty-form { display: flex; align-items: center; gap: 7px; }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 9px 13px;
        font-family: inherit;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-qty { background: #fbe2ec; color: #a83f69; }
    .btn-delete { background: #ffe5e5; color: #b42332; }
    .btn-main { background: #c44f7b; color: white; width: 100%; padding: 14px; }
    .btn-secondary { background: white; color: #c44f7b; border: 1px solid #c44f7b; }
    .btn:disabled { opacity: .45; cursor: not-allowed; }

    .qty-number {
        min-width: 24px;
        text-align: center;
        font-weight: 600;
    }

    .total {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding-top: 18px;
        margin-top: 15px;
        border-top: 2px dashed #efcbd9;
        font-size: 18px;
        font-weight: 700;
        color: #bd4775;
    }

    .alert { padding: 13px 15px; border-radius: 10px; margin-bottom: 15px; }
    .alert-error { background: #ffe8e8; color: #a12d36; }
    .alert-success { background: #e7f8ed; color: #236b3b; }
    .empty { text-align: center; padding: 25px 0; color: #876d78; }
    .top-link { color: #c44f7b; text-decoration: none; font-weight: 600; }

    @media (max-width: 520px) {
        h1 { font-size: 24px; }
        .card { padding: 18px; }
        .item { align-items: flex-start; flex-direction: column; }
        .subtotal { align-self: flex-end; }
    }
</style>

</head>
<body>

<div class="container">
    <a class="top-link" href="{{ route('katalog') }}">← Kembali ke Katalog</a>

<h1>Keranjang Pesanan</h1>
<p class="subtitle">Ubah jumlah atau hapus kue sebelum checkout.</p>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<section class="card">
    <h2>Daftar Kue</h2>

    @forelse($items as $item)
        <div class="item">
            <div>
                <div class="item-name">
                    {{ $item['produk']->nama_kue }}
                </div>

                <div class="size-label">
                    Ukuran: {{ $item['ukuran']->nama_ukuran }}
                </div>

                <div class="item-detail">
                    Harga satuan:
                    Rp{{ number_format($item['harga'], 0, ',', '.') }}
                </div>

                <div class="actions">
                    <form class="qty-form"
                          action="{{ route('keranjang.update', $item['key']) }}"
                          method="POST">
                        @csrf

                        <button class="btn btn-qty"
                                type="submit"
                                name="aksi"
                                value="kurang"
                                {{ $item['jumlah'] <= 1 ? 'disabled' : '' }}
                                aria-label="Kurangi jumlah">
                            −
                        </button>

                        <span class="qty-number">{{ $item['jumlah'] }}</span>

                        <button class="btn btn-qty"
                                type="submit"
                                name="aksi"
                                value="tambah"
                                {{ $item['jumlah'] >= 100 ? 'disabled' : '' }}
                                aria-label="Tambah jumlah">
                            +
                        </button>
                    </form>

                    <form action="{{ route('keranjang.hapus', $item['key']) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus ukuran kue ini dari keranjang?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-delete">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="subtotal">
                Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
            </div>
        </div>
    @empty
        <div class="empty">
            <p>Keranjang kamu masih kosong.</p>
            <a href="{{ route('katalog') }}" class="btn btn-main">
                Pilih Kue
            </a>
        </div>
    @endforelse

    @if(count($items) > 0)
        <div class="total">
            <span>Total Tagihan</span>
            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <form action="{{ route('keranjang.kosongkan') }}"
              method="POST"
              style="margin-top: 18px;"
              onsubmit="return confirm('Yakin ingin menghapus semua kue dari keranjang?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-secondary" style="width:100%;">
                Kosongkan Keranjang
            </button>
        </form>

        <a href="{{ route('checkout.keranjang') }}"
           class="btn btn-main"
           style="margin-top: 12px;">
            Lanjut ke Checkout
        </a>
    @endif
</section>

</div>

</body>
</html>
