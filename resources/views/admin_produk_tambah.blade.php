<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Dwiera</title>

```
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
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

    .main {
        margin-left: 235px;
        width: calc(100% - 235px);
        padding: 30px 35px;
    }

    .topbar {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .back {
        text-decoration: none;
        color: #d66f8d;
        font-size: 14px;
    }

    .topbar h1 {
        font-size: 25px;
        color: #444;
    }

    .topbar p {
        margin-top: 5px;
        color: #999;
        font-size: 14px;
    }

    .form-card {
        max-width: 750px;
        background: white;
        border: 1px solid #f2dfe5;
        border-radius: 15px;
        padding: 28px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #555;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #ddd;
        border-radius: 9px;
        outline: none;
        font-size: 14px;
        background: white;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #d66f8d;
        box-shadow: 0 0 0 3px rgba(214, 111, 141, 0.08);
    }

    textarea {
        min-height: 110px;
        resize: vertical;
    }

    .error {
        margin-top: 6px;
        color: #c9577c;
        font-size: 12px;
    }

    .hint {
        margin-top: 6px;
        color: #999;
        font-size: 12px;
    }

    .size-section {
        background: #fff8fa;
        border: 1px solid #f2dfe5;
        padding: 18px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .size-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .size-heading h3 {
        color: #c9577c;
        font-size: 16px;
    }

    .size-item {
        background: white;
        border: 1px solid #f0dfe5;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
    }

    .size-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .size-fields .form-group {
        margin-bottom: 0;
    }

    .btn-add {
        border: none;
        background: #f5b4c7;
        color: white;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-add:hover {
        background: #d66f8d;
    }

    .btn-remove {
        margin-top: 12px;
        border: none;
        background: #fff0f0;
        color: #c9577c;
        padding: 8px 12px;
        border-radius: 7px;
        font-size: 12px;
        cursor: pointer;
    }

    .btn-remove:hover {
        background: #f9dce4;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-save,
    .btn-cancel {
        padding: 12px 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-save {
        border: none;
        background: #d66f8d;
        color: white;
    }

    .btn-save:hover {
        background: #c9577c;
    }

    .btn-cancel {
        background: #f4e8ec;
        color: #777;
    }

    .btn-cancel:hover {
        background: #eadde1;
    }

    .alert-error {
        background: #fff0f0;
        border: 1px solid #f1b7b7;
        color: #a33333;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
            padding: 20px 10px;
        }

        .admin-label {
            display: none;
        }

        .menu a {
            justify-content: center;
            padding: 13px 5px;
        }

        .menu a span:not(.menu-icon) {
            display: none;
        }

        .logout {
            left: 10px;
            right: 10px;
        }

        .logout button {
            text-align: center;
            padding: 13px 5px;
        }

        .logout button span:last-child {
            display: none;
        }

        .main {
            margin-left: 70px;
            width: calc(100% - 70px);
            padding: 20px;
        }

        .size-fields {
            grid-template-columns: 1fr;
        }

        .size-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
```

</head>

<body>

<div class="layout">

```
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

        <a href="{{ route('admin.produk') }}" class="active">
            <span class="menu-icon">🎂</span>
            <span>Produk</span>
        </a>

        <a href="{{ route('admin.pembayaran') }}">
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

            <a href="{{ route('admin.produk') }}" class="back">
                ← Kembali ke Produk
            </a>

            <h1 style="margin-top: 12px;">Tambah Produk</h1>

            <p>Tambahkan data kue ulang tahun baru ke Dwiera.</p>

        </div>
    </div>

    <div class="form-card">

        @if ($errors->any())
            <div class="alert-error">
                <strong>Periksa kembali data produk:</strong>
                <ul style="padding-left: 20px; margin-top: 8px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.produk.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="form-group">

                <label for="nama_kue">Nama Kue</label>

                <input
                    type="text"
                    id="nama_kue"
                    name="nama_kue"
                    value="{{ old('nama_kue') }}"
                    placeholder="Contoh: Strawberry Cake"
                    required
                >

                @error('nama_kue')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-group">

                <label for="deskripsi">Deskripsi</label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    placeholder="Masukkan deskripsi kue..."
                >{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- UKURAN DAN HARGA -->

            <div class="size-section">

                <div class="size-heading">

                    <div>
                        <h3>Ukuran dan Harga Kue</h3>
                        <p class="hint">
                            Tambahkan beberapa ukuran beserta harga masing-masing.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn-add"
                        id="tambahUkuran"
                    >
                        + Tambah Ukuran
                    </button>

                </div>

                <div id="daftarUkuran">

                    @php
                        $ukuranLama = old('ukuran_produk', [
                            ['nama_ukuran' => '', 'harga' => '', 'status' => 'aktif']
                        ]);
                    @endphp

                    @foreach ($ukuranLama as $index => $ukuran)
                        <div class="size-item">

                            <div class="size-fields">

                                <div class="form-group">

                                    <label>Nama Ukuran</label>

                                    <input
                                        type="text"
                                        name="ukuran_produk[{{ $index }}][nama_ukuran]"
                                        value="{{ $ukuran['nama_ukuran'] ?? '' }}"
                                        placeholder="Contoh: 16 cm"
                                        required
                                    >

                                </div>

                                <div class="form-group">

                                    <label>Harga (Rp)</label>

                                    <input
                                        type="number"
                                        name="ukuran_produk[{{ $index }}][harga]"
                                        value="{{ $ukuran['harga'] ?? '' }}"
                                        placeholder="Contoh: 150000"
                                        min="0"
                                        required
                                    >

                                </div>

                            </div>

                            <input
                                type="hidden"
                                name="ukuran_produk[{{ $index }}][status]"
                                value="aktif"
                            >

                            <button
                                type="button"
                                class="btn-remove"
                                onclick="hapusUkuran(this)"
                                style="{{ count($ukuranLama) <= 1 ? 'display:none;' : '' }}"
                            >
                                Hapus Ukuran
                            </button>

                        </div>
                    @endforeach

                </div>

                @error('ukuran_produk')
                    <div class="error">{{ $message }}</div>
                @enderror

                @error('ukuran_produk.*.nama_ukuran')
                    <div class="error">{{ $message }}</div>
                @enderror

                @error('ukuran_produk.*.harga')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- GAMBAR -->

            <div class="form-group">

                <label for="gambar">Gambar Kue</label>

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <div class="hint">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                </div>

                @error('gambar')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- STATUS -->

            <div class="form-group">

                <label for="status">Status Produk</label>

                <select id="status" name="status" required>

                    <option
                        value="aktif"
                        {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}
                    >
                        Aktif
                    </option>

                    <option
                        value="nonaktif"
                        {{ old('status') === 'nonaktif' ? 'selected' : '' }}
                    >
                        Tidak Aktif
                    </option>

                </select>

                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <div class="form-actions">

                <button type="submit" class="btn-save">
                    Simpan Produk
                </button>

                <a href="{{ route('admin.produk') }}" class="btn-cancel">
                    Batal
                </a>

            </div>

        </form>

    </div>

</main>
```

</div>

<script>
    let nomorUkuran = {{ count($ukuranLama) }};

    document.getElementById('tambahUkuran').addEventListener('click', function () {

        const daftar = document.getElementById('daftarUkuran');

        const item = document.createElement('div');

        item.className = 'size-item';

        item.innerHTML = `
            <div class="size-fields">

                <div class="form-group">
                    <label>Nama Ukuran</label>
                    <input
                        type="text"
                        name="ukuran_produk[${nomorUkuran}][nama_ukuran]"
                        placeholder="Contoh: 20 cm"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input
                        type="number"
                        name="ukuran_produk[${nomorUkuran}][harga]"
                        placeholder="Contoh: 200000"
                        min="0"
                        required
                    >
                </div>

            </div>

            <input
                type="hidden"
                name="ukuran_produk[${nomorUkuran}][status]"
                value="aktif"
            >

            <button
                type="button"
                class="btn-remove"
                onclick="hapusUkuran(this)"
            >
                Hapus Ukuran
            </button>
        `;

        daftar.appendChild(item);

        nomorUkuran++;

        perbaruiTombolHapus();
    });

    function hapusUkuran(button) {

        const semuaUkuran = document.querySelectorAll('.size-item');

        if (semuaUkuran.length <= 1) {
            alert('Minimal harus ada satu ukuran.');
            return;
        }

        button.closest('.size-item').remove();

        perbaruiTombolHapus();
    }

    function perbaruiTombolHapus() {

        const semuaUkuran = document.querySelectorAll('.size-item');

        semuaUkuran.forEach(function (item) {

            const tombol = item.querySelector('.btn-remove');

            tombol.style.display =
                semuaUkuran.length > 1 ? 'inline-block' : 'none';

        });
    }
</script>

</body>
</html>
