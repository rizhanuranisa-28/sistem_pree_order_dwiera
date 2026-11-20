<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kue Custom - Dwiera Cake</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px 14px; background: #fff5f8; color: #50343f; font-family: Arial, sans-serif; }
        .container { max-width: 960px; margin: auto; }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
        h1, h2 { color: #c44f7b; }
        h1 { margin-bottom: 5px; }
        .card { margin-bottom: 16px; padding: 22px; border-radius: 16px; background: white; box-shadow: 0 5px 20px rgba(170,80,115,.08); }
        a { color: #bd4775; text-decoration: none; font-weight: 600; }
        .meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 24px; color: #765d68; line-height: 1.55; }
        .meta strong { color: #50343f; }
        .quote { margin-top: 14px; padding: 14px; border-radius: 10px; background: #fff0f5; line-height: 1.7; }
        .status { display: inline-block; padding: 6px 11px; border-radius: 20px; background: #fff0d9; color: #99641f; font-size: 13px; font-weight: 700; }
        .status.disetujui { background: #e3f7ed; color: #398363; }
        .status.ditolak, .status.dibatalkan { background: #ffe8e8; color: #a12d36; }
        .btn { display: inline-block; margin: 12px 8px 0 0; padding: 10px 14px; border: 0; border-radius: 9px; background: #c44f7b; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .btn.secondary { background: #f3e5eb; color: #8e4a64; }
        .btn.danger { background: #a12d36; }
        .alert { margin-bottom: 15px; padding: 12px; border-radius: 10px; background: #e7f8ed; color: #236b3b; }
        .notification { margin: 12px 0; padding: 12px 14px; border: 1px solid #f0c4d2; border-radius: 10px; background: #fff0f5; color: #8e3f5b; line-height: 1.55; }
        .notification.danger { border-color: #efb8b8; background: #fff0f0; color: #a12d36; }
        .empty { padding: 32px; border-radius: 16px; background: white; text-align: center; color: #876d78; }
        @media (max-width: 600px) { .meta { grid-template-columns: 1fr; } .card { padding: 18px; } }
    </style>
</head>
<body>
<main class="container">
    <div class="top">
        <div><h1>Riwayat Permintaan Custom</h1><p>Periksa status dan penawaran harga kue custom kamu.</p></div>
        <a href="{{ route('custom-orders.create') }}">+ Buat Permintaan</a>
    </div>
    @if (session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert">{{ session('error') }}</div>@endif
    <p><a href="{{ route('katalog') }}">← Kembali ke Katalog</a></p>

    @forelse ($permintaan as $item)
        @php
            $statusLabel = [
                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                'menunggu_persetujuan_harga' => 'Menunggu Persetujuan Harga',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'dibatalkan' => 'Dibatalkan',
            ][$item->status] ?? $item->status;
        @endphp
        <article class="card">
            <h2>Permintaan #{{ $item->id_custom_order }}</h2>
            <p><span class="status {{ $item->status }}">{{ $statusLabel }}</span></p>
            @if ($item->status === 'menunggu_persetujuan_harga')
                <div class="notification" role="status">Penawaran harga baru dari admin tersedia. Periksa rincian dan pilih tindakan di bawah.</div>
            @elseif ($item->status === 'disetujui' && !$item->id_pesanan)
                <div class="notification" role="status">Harga sudah disetujui. Lanjutkan ke checkout untuk memilih jadwal dan pembayaran.</div>
            @elseif ($item->status === 'ditolak')
                <div class="notification danger" role="alert">Permintaan custom ini ditolak admin. Periksa alasan penolakan di bawah.</div>
            @endif
            <div class="meta">
                <div><strong>Ukuran:</strong> {{ $item->ukuran_cm }} cm</div>
                <div><strong>Tema/warna:</strong> {{ $item->tema }}</div>
                <div><strong>Tulisan:</strong> {{ $item->tulisan }}</div>
                <div><strong>Desain dekorasi:</strong> {{ $item->dekorasi }}</div>
                @if ($item->foto_referensi)
                    <div><a href="{{ route('custom-orders.reference', $item->id_custom_order) }}" target="_blank" rel="noopener">Lihat foto referensi</a></div>
                @endif
                @if ($item->catatan_pelanggan)
                    <div><strong>Catatan kamu:</strong> {{ $item->catatan_pelanggan }}</div>
                @endif
            </div>

            @if ($item->status === 'menunggu_persetujuan_harga' || $item->status === 'disetujui')
                <div class="quote">
                    <div>Harga dasar: <strong>Rp{{ number_format($item->harga_dasar, 0, ',', '.') }}</strong></div>
                    <div>Biaya dekorasi: <strong>Rp{{ number_format($item->biaya_dekorasi, 0, ',', '.') }}</strong></div>
                    <div>Total harga: <strong>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</strong></div>
                    @if ($item->catatan_admin)<div>Catatan admin: {{ $item->catatan_admin }}</div>@endif
                </div>
            @elseif ($item->status === 'ditolak' && $item->catatan_admin)
                <div class="quote"><strong>Alasan penolakan:</strong> {{ $item->catatan_admin }}</div>
            @elseif ($item->status === 'menunggu_konfirmasi')
                <div class="quote">Admin sedang memeriksa desain. Harga dasar Rp{{ number_format($item->harga_dasar, 0, ',', '.') }} belum termasuk biaya dekorasi.</div>
            @endif

            @if ($item->id_pesanan && $item->pesanan && $item->pesanan->pembayaran
                && !$item->pesanan->pembayaran->tanggal_bayar
                && $item->pesanan->pembayaran->status_bukti === 'dikembalikan')
                <div class="notification danger" role="alert">
                    <strong>Notifikasi pembayaran: bukti {{ strtolower($item->pesanan->pembayaran->metode_pembayaran) === 'qris' ? 'QRIS' : 'transfer' }} dikembalikan admin.</strong>
                    <div>{{ $item->pesanan->pembayaran->alasan_pengembalian ?: 'Silakan unggah ulang bukti pembayaran yang benar.' }}</div>
                    <a class="btn" href="{{ route('pesanan.bukti.form', $item->id_pesanan) }}">Unggah Ulang Bukti</a>
                    <a class="btn secondary" href="{{ route('pesanan.pelanggan') }}">Lihat Pesanan Saya</a>
                </div>
            @endif

            @if ($item->status === 'menunggu_persetujuan_harga')
                <form action="{{ route('custom-orders.accept', $item->id_custom_order) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn" type="submit">Setuju dengan Harga</button>
                </form>
                <form action="{{ route('custom-orders.cancel', $item->id_custom_order) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn secondary" type="submit">Tidak Setuju dengan Harga</button>
                </form>
            @elseif ($item->status === 'disetujui' && !$item->id_pesanan)
                <a class="btn" href="{{ route('custom-orders.checkout', $item->id_custom_order) }}">Lanjut ke Checkout</a>
                <form action="{{ route('custom-orders.cancel', $item->id_custom_order) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn secondary" type="submit">Batalkan Permintaan</button>
                </form>
            @elseif ($item->status === 'menunggu_konfirmasi')
                <form action="{{ route('custom-orders.cancel', $item->id_custom_order) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn secondary" type="submit">Batalkan Permintaan</button>
                </form>
            @elseif ($item->id_pesanan)
                <p class="quote">Pesanan checkout: #{{ $item->id_pesanan }} — status pesanan {{ $item->pesanan->status_pesanan ?? 'tersimpan' }}.</p>
            @endif
        </article>
    @empty
        <div class="empty">Kamu belum memiliki permintaan kue custom.</div>
    @endforelse
</main>
</body>
</html>
