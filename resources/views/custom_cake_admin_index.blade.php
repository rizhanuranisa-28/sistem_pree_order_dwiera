<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Kue Custom - Admin Dwiera</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px 14px; background: #fff5f8; color: #50343f; font-family: Arial, sans-serif; }
        .container { max-width: 1100px; margin: auto; }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
        h1 { color: #c44f7b; }
        a { color: #bd4775; text-decoration: none; font-weight: 600; }
        .alert { padding: 12px 14px; margin-bottom: 15px; border-radius: 10px; background: #e7f8ed; color: #236b3b; }
        .alert.error { background: #ffe8e8; color: #a12d36; }
        .price-settings { margin-bottom: 18px; padding: 18px; border: 1px solid rgba(196,79,123,.2); border-radius: 14px; background: rgba(233,160,181,.12); }
        .price-settings h2 { margin-bottom: 6px; color: #bd4775; font-size: 18px; }
        .price-settings p { margin-bottom: 14px; color: #755c67; font-size: 13px; line-height: 1.5; }
        .price-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px; }
        .price-field label { display: block; margin-bottom: 6px; color: #684553; font-size: 13px; font-weight: 700; }
        .price-field input { width: 100%; padding: 10px 11px; border: 1px solid #e8c6d5; border-radius: 8px; background: white; color: #50343f; font: inherit; }
        .price-edit-line { display: flex; align-items: center; gap: 7px; }
        .price-edit-line input { min-width: 0; }
        .new-price-list { display: grid; gap: 10px; margin-top: 12px; }
        .new-price-entry { padding: 12px; border: 1px dashed rgba(196,79,123,.35); border-radius: 10px; background: rgba(255,255,255,.72); }
        .add-size, .remove-size { padding: 9px 12px; border: 1px solid #c44f7b; border-radius: 8px; background: white; color: #bd4775; font: inherit; font-weight: 700; cursor: pointer; }
        .remove-size { margin-top: 20px; border-color: #e6b3c5; }
        .delete-size { flex: 0 0 auto; padding: 9px 10px; border: 1px solid #e1a8b8; border-radius: 8px; background: #fff0f5; color: #a12d55; font: inherit; font-size: 12px; font-weight: 700; cursor: pointer; }
        .field-error { margin-top: 5px; color: #a12d36; font-size: 12px; }
        .save-prices { margin-top: 14px; padding: 10px 14px; border: 0; border-radius: 8px; background: #c44f7b; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .table-wrap { overflow-x: auto; border-radius: 14px; background: white; box-shadow: 0 5px 20px rgba(170,80,115,.08); }
        table { width: 100%; min-width: 1000px; border-collapse: collapse; }
        th { padding: 13px; border-right: 1px solid rgba(255,255,255,.28); background: #e9a0b5; color: white; text-align: left; }
        th:last-child { border-right: 0; }
        td { padding: 13px; border-bottom: 1px solid #f3e5eb; vertical-align: top; }
        td.design-cell { background: rgba(233,160,181,.16); border-right: 1px solid rgba(196,79,123,.16); border-bottom-color: rgba(196,79,123,.2); }
        tbody tr:hover td.design-cell { background: rgba(233,160,181,.25); }
        .status { display: inline-block; padding: 6px 9px; border-radius: 18px; background: #fff0d9; color: #99641f; font-size: 12px; font-weight: 700; }
        .status.disetujui { background: #e3f7ed; color: #398363; }
        .status.ditolak, .status.dibatalkan { background: #ffe8e8; color: #a12d36; }
        .unread-dot { display: inline-block; width: 9px; height: 9px; margin-left: 6px; border-radius: 50%; background: #d94369; box-shadow: 0 0 0 3px rgba(217,67,105,.13); vertical-align: middle; }
        .empty { padding: 34px; text-align: center; color: #876d78; }
        @media (max-width: 600px) { body { padding: 18px 10px; } }
    </style>
</head>
<body>
<main class="container">
    <div class="top">
        <div>
            <h1>Permintaan Kue Custom</h1>
            <p>Periksa desain pelanggan dan kirim penawaran atau alasan penolakan.</p>
        </div>
        <a href="{{ route('admin') }}">← Dashboard Admin</a>
    </div>
    @if (session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert">{{ session('error') }}</div>@endif
    @if ($errors->any())
        <div class="alert error">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <section class="price-settings">
        <h2>Atur Harga Dasar Kue Custom</h2>
        <p>Harga yang disimpan akan tampil di formulir pelanggan dan berlaku untuk permintaan baru. Permintaan yang sudah masuk tetap memakai harga dasarnya saat diajukan.</p>
        <form action="{{ route('admin.custom-orders.base-prices.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="price-grid">
                @foreach ($hargaDasar as $ukuran => $harga)
                    <div class="price-field">
                        <label for="harga_dasar_{{ $ukuran }}">Ukuran {{ $ukuran }} cm (Rp)</label>
                        <div class="price-edit-line">
                            <input
                                id="harga_dasar_{{ $ukuran }}"
                                name="harga_dasar[{{ $ukuran }}]"
                                type="text"
                                inputmode="numeric"
                                data-currency-input
                                value="{{ old('harga_dasar.'.$ukuran, $harga) }}"
                                placeholder="Contoh: 60.000"
                                required
                            >
                            <button
                                class="delete-size"
                                type="button"
                                data-size="{{ $ukuran }}"
                                data-action="{{ route('admin.custom-orders.base-prices.delete', $ukuran) }}"
                            >Hapus</button>
                        </div>
                        @error('harga_dasar.'.$ukuran)<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
            <div id="new-price-list" class="new-price-list"></div>
            <button id="add-size-button" class="add-size" type="button">+ Tambah Ukuran</button>
            <button class="save-prices" type="submit">Simpan Harga Dasar</button>
        </form>
        <form id="delete-price-form" method="POST" hidden>
            @csrf
            @method('DELETE')
        </form>
    </section>
    <p style="margin-bottom:14px"><a href="{{ route('admin.pesanan') }}">Kelola Pesanan</a></p>
    <div class="table-wrap">
        @if ($permintaan->isEmpty())
            <div class="empty">Belum ada permintaan kue custom.</div>
        @else
            <table>
                <thead>
                    <tr><th>No.</th><th>Pelanggan</th><th>Ukuran</th><th>Tema/Warna</th><th>Tulisan</th><th>Tanggal</th><th>Status</th><th>Rincian Harga</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($permintaan as $item)
                        <tr>
                            <td>
                                #{{ $item->id_custom_order }}
                                @if ($item->status === \App\Models\CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI)
                                    <span class="unread-dot" title="Belum diperiksa admin" aria-label="Belum diperiksa admin"></span>
                                @endif
                            </td>
                            <td>{{ $item->user->nama_users ?? '-' }}</td>
                            <td class="design-cell">{{ $item->ukuran_cm }} cm</td>
                            <td class="design-cell">{{ $item->tema }}</td>
                            <td class="design-cell">{{ $item->tulisan }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td><span class="status {{ $item->status }}">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span></td>
                            <td>
                                Dasar: Rp{{ number_format($item->harga_dasar, 0, ',', '.') }}<br>
                                Dekorasi: {{ $item->biaya_dekorasi === null ? 'Belum ditentukan' : 'Rp' . number_format($item->biaya_dekorasi, 0, ',', '.') }}
                            </td>
                            <td><a href="{{ route('admin.custom-orders.show', $item->id_custom_order) }}">Lihat detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</main>
<script>
    (function () {
        var list = document.getElementById('new-price-list');
        var addButton = document.getElementById('add-size-button');
        var deleteForm = document.getElementById('delete-price-form');

        addButton.addEventListener('click', function () {
            var entry = document.createElement('div');
            entry.className = 'new-price-entry';
            entry.innerHTML = `
                <div class="price-grid">
                    <div class="price-field">
                        <label>Ukuran baru (cm)</label>
                        <input type="number" name="ukuran_baru[]" min="1" max="500" step="1" placeholder="Contoh: 24" required>
                    </div>
                    <div class="price-field">
                        <label>Harga dasar (Rp)</label>
                        <input type="text" name="harga_baru[]" inputmode="numeric" data-currency-input placeholder="Contoh: 175.000" required>
                    </div>
                    <div class="price-field">
                        <button class="remove-size" type="button">Hapus ukuran</button>
                    </div>
                </div>
            `;
            list.appendChild(entry);
        });

        list.addEventListener('click', function (event) {
            var removeButton = event.target.closest('.remove-size');
            if (removeButton) {
                removeButton.closest('.new-price-entry').remove();
            }
        });

        document.querySelectorAll('.delete-size').forEach(function (button) {
            button.addEventListener('click', function () {
                var ukuran = button.getAttribute('data-size');
                if (!window.confirm('Hapus ukuran ' + ukuran + ' cm dari pilihan pelanggan?')) {
                    return;
                }

                deleteForm.action = button.getAttribute('data-action');
                deleteForm.submit();
            });
        });
    })();
</script>
@include('partials.currency_input_formatter')
</body>
</html>
