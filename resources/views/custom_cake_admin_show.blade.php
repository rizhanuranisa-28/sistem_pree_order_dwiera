<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permintaan Custom - Admin Dwiera</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px 14px; background: #fff5f8; color: #50343f; font-family: Arial, sans-serif; }
        .container { max-width: 850px; margin: auto; }
        .card { margin: 16px 0; padding: 24px; border-radius: 16px; background: white; box-shadow: 0 5px 20px rgba(170,80,115,.08); }
        a { color: #bd4775; text-decoration: none; font-weight: 600; }
        h1, h2 { color: #c44f7b; }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; line-height: 1.6; }
        .fields div { overflow-wrap: anywhere; }
        label { display: block; margin: 14px 0 7px; font-weight: 700; }
        input, textarea { width: 100%; min-height: 44px; padding: 10px; border: 1px solid #e8c6d5; border-radius: 9px; font: inherit; }
        textarea { min-height: 95px; resize: vertical; }
        .hint { margin-top: 6px; color: #876d78; font-size: 12px; }
        .btn { display: inline-block; margin-top: 12px; padding: 11px 15px; border: 0; border-radius: 9px; background: #c44f7b; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .btn.danger { background: #a12d36; }
        .alert { padding: 12px; border-radius: 10px; margin: 14px 0; background: #ffe8e8; color: #a12d36; }
        .success { background: #e7f8ed; color: #236b3b; }
        .quote { margin-top: 16px; padding: 14px; border-radius: 10px; background: #fff0f5; line-height: 1.7; }
        img { display: block; max-width: 100%; max-height: 460px; margin-top: 12px; border-radius: 12px; }
        @media (max-width: 600px) { .fields { grid-template-columns: 1fr; } .card { padding: 18px; } }
    </style>
</head>
<body>
<main class="container">
    <a href="{{ route('admin.custom-orders.index') }}">← Kembali ke daftar permintaan</a>
    <div class="card">
        <h1>Permintaan Custom #{{ $permintaan->id_custom_order }}</h1>
        <p>Status: <strong>{{ str_replace('_', ' ', ucfirst($permintaan->status)) }}</strong></p>
        @if (session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="fields">
            <div><strong>Pelanggan:</strong> {{ $permintaan->user->nama_users ?? '-' }}</div>
            <div><strong>Email:</strong> {{ $permintaan->user->email ?? '-' }}</div>
            <div><strong>Ukuran:</strong> {{ $permintaan->ukuran_cm }} cm</div>
            <div><strong>Harga dasar:</strong> Rp{{ number_format($permintaan->harga_dasar, 0, ',', '.') }}</div>
            <div><strong>Tema/warna:</strong> {{ $permintaan->tema }}</div>
            <div><strong>Tulisan:</strong> {{ $permintaan->tulisan }}</div>
            <div><strong>Deskripsi dekorasi:</strong> {{ $permintaan->dekorasi }}</div>
            <div><strong>Catatan pelanggan:</strong> {{ $permintaan->catatan_pelanggan ?: '-' }}</div>
        </div>
        @if ($permintaan->foto_referensi)
            <p style="margin-top:16px"><a href="{{ route('custom-orders.reference', $permintaan->id_custom_order) }}" target="_blank" rel="noopener">Buka foto referensi</a></p>
            <img src="{{ route('custom-orders.reference', $permintaan->id_custom_order) }}" alt="Foto referensi desain pelanggan">
        @endif
        @if ($permintaan->status === 'menunggu_persetujuan_harga' || $permintaan->status === 'disetujui')
            <div class="quote">
                Harga dasar: Rp{{ number_format($permintaan->harga_dasar, 0, ',', '.') }}<br>
                Biaya dekorasi: Rp{{ number_format($permintaan->biaya_dekorasi, 0, ',', '.') }}<br>
                Total: <strong>Rp{{ number_format($permintaan->total_harga, 0, ',', '.') }}</strong><br>
                Catatan admin: {{ $permintaan->catatan_admin ?: '-' }}
            </div>
        @elseif ($permintaan->catatan_admin)
            <div class="quote"><strong>Alasan penolakan:</strong> {{ $permintaan->catatan_admin }}</div>
        @endif
        @if ($permintaan->id_pesanan)
            <div class="quote">Sudah masuk pesanan #{{ $permintaan->id_pesanan }}. <a href="{{ route('admin.pesanan') }}">Lihat pengelolaan pesanan</a></div>
        @endif
    </div>

    @if ($permintaan->status === 'menunggu_konfirmasi')
        <section class="card">
            <h2>Kirim Penawaran Harga</h2>
            <p>Biaya dekorasi wajib diisi manual. Total dihitung dari harga dasar ditambah biaya dekorasi.</p>
            <form action="{{ route('admin.custom-orders.quote', $permintaan->id_custom_order) }}" method="POST">
                @csrf
                <input type="hidden" name="aksi" value="tawarkan">
                <label for="biaya_dekorasi">Biaya dekorasi (Rp)</label>
                <input id="biaya_dekorasi" name="biaya_dekorasi" type="text" inputmode="numeric" data-currency-input value="{{ old('biaya_dekorasi') }}" data-harga-dasar="{{ $permintaan->harga_dasar }}" placeholder="Contoh: 25.000" required>
                <p class="hint">Perkiraan total: <strong id="totalPenawaran">Rp{{ number_format($permintaan->harga_dasar, 0, ',', '.') }}</strong></p>
                <label for="catatan_admin">Catatan untuk pelanggan (opsional)</label>
                <textarea id="catatan_admin" name="catatan_admin" maxlength="1000">{{ old('catatan_admin') }}</textarea>
                <button class="btn" type="submit">Kirim Penawaran</button>
            </form>
        </section>
        <section class="card">
            <h2>Tolak Permintaan</h2>
            <form action="{{ route('admin.custom-orders.quote', $permintaan->id_custom_order) }}" method="POST">
                @csrf
                <input type="hidden" name="aksi" value="tolak">
                <label for="alasan_penolakan">Alasan penolakan (wajib)</label>
                <textarea id="alasan_penolakan" name="catatan_admin" maxlength="1000" required>{{ old('catatan_admin') }}</textarea>
                <button class="btn danger" type="submit">Tolak Permintaan</button>
            </form>
        </section>
    @endif
</main>
<script>
    (function () {
        var biaya = document.getElementById('biaya_dekorasi');
        var total = document.getElementById('totalPenawaran');
        if (!biaya || !total) {
            return;
        }

        var hargaDasar = Number(biaya.getAttribute('data-harga-dasar'));
        var formatRupiah = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });

        biaya.addEventListener('input', function () {
            var biayaDekorasi = Number(biaya.value.replace(/\./g, '') || 0);
            total.textContent = 'Rp' + formatRupiah.format(hargaDasar + biayaDekorasi);
        });
    })();
</script>
@include('partials.currency_input_formatter')
</body>
</html>
