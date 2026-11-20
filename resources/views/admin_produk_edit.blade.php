<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Edit Produk - Dwiera</title>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        background: #fff7fa;
        color: #333;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }

    .card {
        background: white;
        border: 1px solid #f2dfe5;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 3px 15px rgba(160, 80, 100, 0.08);
    }

    h1 {
        margin-bottom: 25px;
        font-size: 28px;
        color: #444;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #eadde2;
        border-radius: 8px;
        font-size: 15px;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #d66f8d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(214, 111, 141, 0.12);
    }

    textarea {
        min-height: 120px;
        resize: vertical;
    }

    .ukuran-card {
        border: 1px solid #f0dfe5;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 15px;
        background: #fff7fa;
    }

    .ukuran-title {
        font-weight: bold;
        margin-bottom: 15px;
        color: #a64d72;
    }

    .ukuran-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .btn-add {
        background: #d66f8d;
        color: white;
        border: none;
        padding: 11px 16px;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .btn-add:hover,
    .btn-save:hover {
        background: #c9577c;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 12px;
    }

    .current-image {
        margin-top: 10px;
    }

    .current-image img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #f0dfe5;
    }

    .error {
        color: #d00;
        font-size: 14px;
        margin-top: 5px;
    }

    .buttons {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        font-size: 15px;
    }

    .btn-save {
        background: #d66f8d;
        color: white;
    }

    .btn-cancel {
        background: #fff0f5;
        color: #c66b88;
    }

    .btn-cancel:hover {
        background: #f9dbe6;
    }

    .info {
        background: #fff0f5;
        color: #a64d72;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 18px;
    }

    @media (max-width: 600px) {
        .container {
            margin: 15px auto;
            padding: 10px;
        }

        .card {
            padding: 20px;
        }

        .ukuran-grid {
            grid-template-columns: 1fr;
        }

        .buttons {
            flex-direction: column;
        }
    }
</style>


</head>

<body>

<div class="container">


<div class="card">

    <h1>Edit Produk</h1>

    @if ($errors->any())
        <div style="margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.produk.update', $produk->id_produk) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_kue">Nama Kue</label>

            <input
                type="text"
                id="nama_kue"
                name="nama_kue"
                value="{{ old('nama_kue', $produk->nama_kue) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>

            <textarea
                id="deskripsi"
                name="deskripsi"
            >{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="form-group">

            <label>Ukuran dan Harga Produk</label>

            <div class="info">
                Tambahkan beberapa pilihan ukuran untuk satu produk.
                Setiap ukuran memiliki harga tersendiri.
            </div>

            @php
                $daftarUkuran = old('ukuran_produk');

                if ($daftarUkuran === null) {
                    $daftarUkuran = $produk->ukuranProduks->map(function ($ukuran) {
                        return [
                            'id_ukuran' => $ukuran->id_ukuran,
                            'nama_ukuran' => $ukuran->nama_ukuran,
                            'harga' => $ukuran->harga,
                            'status' => $ukuran->status,
                        ];
                    })->toArray();
                }
            @endphp

            <div id="daftar-ukuran">

                @foreach ($daftarUkuran as $index => $ukuran)

                    <div class="ukuran-card">

                        <div class="ukuran-title">
                            Pilihan Ukuran
                        </div>

                        @if (!empty($ukuran['id_ukuran']))
                            <input
                                type="hidden"
                                name="ukuran_produk[{{ $index }}][id_ukuran]"
                                value="{{ $ukuran['id_ukuran'] }}"
                            >
                        @endif

                        <div class="ukuran-grid">

                            <div>
                                <label>Nama Ukuran</label>

                                <input
                                    type="text"
                                    name="ukuran_produk[{{ $index }}][nama_ukuran]"
                                    value="{{ $ukuran['nama_ukuran'] ?? '' }}"
                                    placeholder="Contoh: 16 cm"
                                    required
                                >
                            </div>

                            <div>
                                <label>Harga (Rp)</label>

                                <input
                                    type="text"
                                    name="ukuran_produk[{{ $index }}][harga]"
                                    value="{{ $ukuran['harga'] ?? '' }}"
                                    inputmode="numeric"
                                    data-currency-input
                                    placeholder="Contoh: 150.000"
                                    required
                                >
                            </div>

                            <div>
                                <label>Status Ukuran</label>

                                <select name="ukuran_produk[{{ $index }}][status]" required>
                                    <option value="tersedia"
                                        {{ in_array(strtolower((string) ($ukuran['status'] ?? 'aktif')), ['aktif', 'tersedia'], true) ? 'selected' : '' }}>
                                        Tersedia
                                    </option>

                                    <option value="habis"
                                        {{ in_array(strtolower((string) ($ukuran['status'] ?? '')), ['nonaktif', 'habis'], true) ? 'selected' : '' }}>
                                        Habis
                                    </option>
                                </select>
                            </div>

                        </div>

                        @if (empty($ukuran['id_ukuran']))
                            <button type="button" class="btn-remove" onclick="hapusUkuran(this)">
                                Hapus Ukuran
                            </button>
                        @endif

                    </div>

                @endforeach

            </div>

            <button type="button" class="btn-add" onclick="tambahUkuran()">
                + Tambah Ukuran
            </button>

        </div>

        <div class="form-group">
            <label for="gambar">Gambar Produk</label>

            <input
                type="file"
                id="gambar"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp"
            >

            @if ($produk->gambar)
                <div class="current-image">
                    <p style="margin-bottom: 8px;">Gambar saat ini:</p>

                    <img
                        src="{{ $produk->gambar_url }}"
                        alt="{{ $produk->nama_kue }}"
                    >
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="status">Status Produk</label>

            <select id="status" name="status" required>

                <option
                    value="tersedia"
                    {{ in_array(strtolower((string) old('status', $produk->status)), ['aktif', 'tersedia'], true) ? 'selected' : '' }}
                >
                    Tersedia
                </option>

                <option
                    value="habis"
                    {{ in_array(strtolower((string) old('status', $produk->status)), ['nonaktif', 'habis'], true) ? 'selected' : '' }}
                >
                    Habis
                </option>

            </select>
        </div>

        <div class="buttons">

            <button type="submit" class="btn btn-save">
                Simpan Perubahan
            </button>

            <a href="{{ route('admin.produk') }}" class="btn btn-cancel">
                Batal
            </a>

        </div>

    </form>

</div>


</div>

<script>
    let indexUkuran = {{ count($daftarUkuran) }};

    function tambahUkuran() {

        const daftar = document.getElementById('daftar-ukuran');

        const card = document.createElement('div');

        card.className = 'ukuran-card';

        card.innerHTML = `
            <div class="ukuran-title">
                Pilihan Ukuran Baru
            </div>

            <div class="ukuran-grid">

                <div>
                    <label>Nama Ukuran</label>

                    <input
                        type="text"
                        name="ukuran_produk[${indexUkuran}][nama_ukuran]"
                        placeholder="Contoh: 20 cm"
                        required
                    >
                </div>

                <div>
                    <label>Harga (Rp)</label>

                    <input
                        type="text"
                        name="ukuran_produk[${indexUkuran}][harga]"
                        inputmode="numeric"
                        data-currency-input
                        placeholder="Contoh: 200.000"
                        required
                    >
                </div>

                <div>
                    <label>Status Ukuran</label>

                    <select name="ukuran_produk[${indexUkuran}][status]" required>
                        <option value="tersedia" selected>Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>

            </div>

            <button type="button" class="btn-remove" onclick="hapusUkuran(this)">
                Hapus Ukuran
            </button>
        `;

        daftar.appendChild(card);

        indexUkuran++;
    }

    function hapusUkuran(button) {
        button.closest('.ukuran-card').remove();
    }
</script>

@include('partials.currency_input_formatter')

</body>
</html>
