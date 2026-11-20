<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kue Custom - Dwiera Cake</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px 14px; background: #fff5f8; color: #50343f; font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 760px; margin: auto; }
        .card { padding: 26px; border-radius: 18px; background: white; box-shadow: 0 5px 20px rgba(170,80,115,.08); }
        a { color: #c44f7b; font-weight: 600; text-decoration: none; }
        h1 { margin: 18px 0 8px; color: #c44f7b; }
        .intro, .hint { color: #876d78; line-height: 1.6; }
        .group { margin-top: 18px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 700; }
        input, select, textarea { width: 100%; min-height: 46px; padding: 11px 12px; border: 1px solid #e8c6d5; border-radius: 10px; background: white; color: #50343f; font: inherit; }
        textarea { min-height: 100px; resize: vertical; }
        .hint { margin-top: 6px; font-size: 12px; }
        .price { margin-top: 8px; color: #bd4775; font-size: 17px; font-weight: 700; }
        .notice { margin-top: 15px; padding: 12px; border-radius: 10px; background: #fff0f5; color: #8e4a64; font-size: 13px; line-height: 1.6; }
        .error { margin-top: 6px; color: #b42318; font-size: 12px; }
        .alert { padding: 12px 14px; margin: 14px 0; border-radius: 10px; background: #ffe8e8; color: #a12d36; }
        .btn { width: 100%; margin-top: 22px; padding: 14px; border: 0; border-radius: 11px; background: #c44f7b; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .btn:hover { background: #a83f69; }
        @media (max-width: 480px) { .card { padding: 19px; } h1 { font-size: 23px; } }
    </style>
</head>
<body>
<main class="container">
    <a href="{{ route('katalog') }}">← Kembali ke Katalog</a>
    <h1>Pesan Kue Custom</h1>
    <p class="intro">Ceritakan desain yang kamu inginkan. Admin akan memeriksa permintaan dan mengirimkan penawaran harga sebelum pesanan dapat dilanjutkan.</p>

    @if ($errors->any())
        <div class="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form class="card" action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="group">
            <label for="ukuran_cm">Ukuran kue</label>
            <select id="ukuran_cm" name="ukuran_cm" required>
                <option value="">Pilih ukuran</option>
                @foreach ($hargaDasar as $ukuran => $harga)
                    <option value="{{ $ukuran }}" data-harga="{{ $harga }}" @selected((string) old('ukuran_cm') === (string) $ukuran)>
                        {{ $ukuran }} cm
                    </option>
                @endforeach
            </select>
            <div class="price">Harga dasar: <span id="hargaDasar">Pilih ukuran</span></div>
            @error('ukuran_cm')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="notice">Harga di atas adalah harga dasar kue dan belum termasuk biaya dekorasi. Biaya dekorasi ditentukan manual oleh admin berdasarkan desain, bahan, dan tingkat kesulitan. Kamu akan menyetujui penawaran terlebih dahulu sebelum checkout.</div>

        <div class="group">
            <label for="tema">Tema atau warna kue</label>
            <input id="tema" name="tema" value="{{ old('tema') }}" maxlength="100" required>
            @error('tema')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="group">
            <label for="tulisan">Tulisan pada kue</label>
            <input id="tulisan" name="tulisan" value="{{ old('tulisan') }}" maxlength="100" required>
            @error('tulisan')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="group">
            <label for="dekorasi">Deskripsi dekorasi tambahan</label>
            <textarea id="dekorasi" name="dekorasi" maxlength="2000" required>{{ old('dekorasi') }}</textarea>
            @error('dekorasi')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="group">
            <label for="foto_referensi">Foto referensi desain (opsional)</label>
            <input id="foto_referensi" name="foto_referensi" type="file" accept="image/jpeg,image/png,image/webp">
            <div class="hint">Format JPG, PNG, atau WEBP; ukuran maksimal 5 MB.</div>
            @error('foto_referensi')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="group">
            <label for="catatan_pelanggan">Catatan tambahan (opsional)</label>
            <textarea id="catatan_pelanggan" name="catatan_pelanggan" maxlength="1000">{{ old('catatan_pelanggan') }}</textarea>
            @error('catatan_pelanggan')<div class="error">{{ $message }}</div>@enderror
        </div>

        <button class="btn" type="submit">Kirim Permintaan Custom</button>
    </form>

</main>
<script>
    (function () {
        var ukuran = document.getElementById('ukuran_cm');
        var harga = document.getElementById('hargaDasar');
        function perbaruiHarga() {
            var option = ukuran.options[ukuran.selectedIndex];
            var nilai = option ? option.getAttribute('data-harga') : null;
            harga.textContent = nilai
                ? 'Rp' + Number(nilai).toLocaleString('id-ID')
                : 'Pilih ukuran';
        }
        ukuran.addEventListener('change', perbaruiHarga);
        perbaruiHarga();
    })();
</script>
</body>
</html>
