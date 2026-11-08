<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - Dwiera</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff5f8;
            color: #513640;
            padding: 30px 16px;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #d65a86;
            margin-bottom: 8px;
        }

        .header p {
            color: #876774;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(180, 90, 125, 0.10);
        }

        .order-number {
            background: #fff0f5;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 22px;
            color: #c34e7b;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #e8c5d2;
            border-radius: 9px;
            background: white;
            color: #513640;
            font-size: 15px;
            font-family: inherit;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
            line-height: 1.6;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #d65a86;
            box-shadow: 0 0 0 3px rgba(214, 90, 134, 0.12);
        }

        .help-text {
            font-size: 12px;
            color: #876774;
            margin-top: 7px;
        }

        /* BARU: daftar baris kue */
        .item-row {
            background: #fff9fb;
            border: 1px solid #f1d5df;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .item-row select {
            margin-bottom: 10px;
        }

        .item-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .qty {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .qty input {
            width: 70px;
            text-align: center;
            padding: 10px 6px;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 9px;
            background: #f8dce6;
            color: #c34e7b;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .qty-btn:hover {
            background: #f3c8d8;
        }

        .row-subtotal {
            font-size: 14px;
            font-weight: bold;
            color: #c34e7b;
        }

        .btn-hapus {
            border: none;
            background: #fde8ec;
            color: #b23a56;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-hapus:hover {
            background: #f9d3db;
        }

        .btn-hapus:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-tambah {
            width: 100%;
            border: 2px dashed #e8a9c0;
            background: transparent;
            color: #c34e7b;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-tambah:hover {
            background: #fff0f5;
        }

        .price-box {
            background: #fff5f8;
            border-radius: 12px;
            padding: 18px;
            margin: 24px 0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .price-row:last-of-type {
            margin-bottom: 0;
            padding-top: 12px;
            border-top: 1px solid #ebcbd7;
            font-size: 18px;
            font-weight: bold;
            color: #c34e7b;
        }

        .alert {
            padding: 13px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-error {
            background: #fff0f0;
            color: #a52a2a;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            flex: 1;
            text-align: center;
            padding: 13px 18px;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background: #d65a86;
            color: white;
        }

        .btn-primary:hover {
            background: #bf4773;
        }

        .btn-secondary {
            background: #f5e5eb;
            color: #704453;
        }

        @media (max-width: 480px) {
            .card {
                padding: 20px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @php
        // Baris awal: dari isian sebelumnya (kalau validasi gagal) atau dari pesanan.
        $barisAwal = old('items');

        if (!is_array($barisAwal) || count($barisAwal) === 0) {
            $barisAwal = $pesanan->detailPesanans
                ->map(fn ($d) => [
                    'id_produk' => $d->id_produk,
                    'jumlah' => $d->jumlah,
                ])
                ->values()
                ->all();
        }

        if (count($barisAwal) === 0) {
            $barisAwal = [['id_produk' => '', 'jumlah' => 1]];
        }

        $barisAwal = array_values($barisAwal);

        $tanggalAwal = $pesanan->tanggal_pengambilan
            ? \Carbon\Carbon::parse($pesanan->tanggal_pengambilan)->format('Y-m-d')
            : '';

        $jamAwal = $pesanan->jadwalPengambilan
            ? substr((string) $pesanan->jadwalPengambilan->jam_pengambilan, 0, 5)
            : '';
    @endphp

    <div class="container">

        <div class="header">
            <h1>🎂 Edit Pesanan</h1>
            <p>Ubah pilihan kue, jadwal, dan catatan pesananmu.</p>
        </div>

        <div class="card">

            <div class="order-number">
                Nomor Pesanan: #{{ $pesanan->id_pesanan }}
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul style="margin: 8px 0 0 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pesanan.update', $pesanan->id_pesanan) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Pilih Kue</label>

                    <div id="daftarKue">
                        @foreach ($barisAwal as $i => $baris)
                            <div class="item-row">
                                <select name="items[{{ $i }}][id_produk]" class="sel-produk" required>
                                    <option value="">-- Pilih Kue --</option>
                                    @foreach ($produk as $item)
                                        <option
                                            value="{{ $item->id_produk }}"
                                            data-harga="{{ $item->harga }}"
                                            {{ (string) ($baris['id_produk'] ?? '') === (string) $item->id_produk ? 'selected' : '' }}
                                        >
                                            {{ $item->nama_kue }}
                                            — Rp{{ number_format($item->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="item-bottom">
                                    <div class="qty">
                                        <button type="button" class="qty-btn btn-minus" aria-label="Kurangi">−</button>
                                        <input
                                            type="number"
                                            name="items[{{ $i }}][jumlah]"
                                            class="inp-jumlah"
                                            min="1"
                                            max="100"
                                            value="{{ $baris['jumlah'] ?? 1 }}"
                                            required
                                        >
                                        <button type="button" class="qty-btn btn-plus" aria-label="Tambah">+</button>
                                    </div>

                                    <span class="row-subtotal">Rp0</span>

                                    <button type="button" class="btn-hapus">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn-tambah" id="btnTambah">+ Tambah Kue</button>

                    <p class="help-text">
                        Pilihan hanya menampilkan produk yang berstatus aktif.
                        Satu jenis kue cukup dipilih sekali, naikkan jumlahnya dengan tombol +.
                    </p>
                </div>

                <div class="form-group">
                    <label for="tanggal_pengambilan">Tanggal Pengambilan</label>

                    <input
                        type="date"
                        name="tanggal_pengambilan"
                        id="tanggal_pengambilan"
                        value="{{ old('tanggal_pengambilan', $tanggalAwal) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="jam_pengambilan">Jam Pengambilan</label>

                    <input
                        type="time"
                        name="jam_pengambilan"
                        id="jam_pengambilan"
                        value="{{ old('jam_pengambilan', $jamAwal) }}"
                        required
                    >

                    <p class="help-text">
                        Jam buka pengambilan 08:00 sampai 20:00, minimal 3 jam dari sekarang.
                    </p>
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan Pesanan</label>

                    <textarea
                        name="catatan"
                        id="catatan"
                        rows="4"
                        maxlength="500"
                        placeholder="Contoh: Birthday Cake tulisan Happy Birthday Aulia"
                    >{{ old('catatan', $pesanan->catatan) }}</textarea>

                    <p class="help-text">Opsional, maksimal 500 karakter.</p>
                </div>

                <div class="price-box">
                    <div class="price-row">
                        <span>Jenis Kue</span>
                        <strong id="jenisTampil">0</strong>
                    </div>

                    <div class="price-row">
                        <span>Total Jumlah Kue</span>
                        <strong id="jumlahTampil">0</strong>
                    </div>

                    <div class="price-row">
                        <span>Perkiraan Total</span>
                        <strong id="totalHarga">Rp0</strong>
                    </div>

                    <p class="help-text" style="margin-top: 12px;">
                        Perhitungan di halaman ini merupakan perkiraan.
                        Total akhir dihitung kembali oleh server.
                    </p>
                </div>

                <div class="actions">
                    <a href="{{ route('pesanan.pelanggan') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        💗 Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Cetakan baris baru untuk tombol "+ Tambah Kue" --}}
    <template id="templateBaris">
        <div class="item-row">
            <select name="items[__INDEX__][id_produk]" class="sel-produk" required>
                <option value="">-- Pilih Kue --</option>
                @foreach ($produk as $item)
                    <option value="{{ $item->id_produk }}" data-harga="{{ $item->harga }}">
                        {{ $item->nama_kue }}
                        — Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>

            <div class="item-bottom">
                <div class="qty">
                    <button type="button" class="qty-btn btn-minus" aria-label="Kurangi">−</button>
                    <input type="number" name="items[__INDEX__][jumlah]"
                           class="inp-jumlah" min="1" max="100" value="1" required>
                    <button type="button" class="qty-btn btn-plus" aria-label="Tambah">+</button>
                </div>

                <span class="row-subtotal">Rp0</span>

                <button type="button" class="btn-hapus">Hapus</button>
            </div>
        </div>
    </template>

    <script>
        const daftarKue = document.getElementById('daftarKue');
        const templateBaris = document.getElementById('templateBaris');
        const btnTambah = document.getElementById('btnTambah');
        const jenisTampil = document.getElementById('jenisTampil');
        const jumlahTampil = document.getElementById('jumlahTampil');
        const totalHarga = document.getElementById('totalHarga');

        let indexBerikut = daftarKue.querySelectorAll('.item-row').length;

        function formatRupiah(nilai) {
            return 'Rp' + Math.round(nilai).toLocaleString('id-ID');
        }

        function ambilJumlah(baris) {
            const nilai = parseInt(baris.querySelector('.inp-jumlah').value, 10);
            return isNaN(nilai) ? 0 : Math.min(100, Math.max(0, nilai));
        }

        function ambilHarga(baris) {
            const select = baris.querySelector('.sel-produk');
            const opsi = select.options[select.selectedIndex];
            return Number(opsi && opsi.dataset.harga ? opsi.dataset.harga : 0);
        }

        // Satu jenis kue tidak boleh dipilih di dua baris.
        function perbaruiPilihan() {
            const baris = daftarKue.querySelectorAll('.item-row');
            const terpilih = [];

            baris.forEach(function (b) {
                const nilai = b.querySelector('.sel-produk').value;
                if (nilai) { terpilih.push(nilai); }
            });

            baris.forEach(function (b) {
                const select = b.querySelector('.sel-produk');

                Array.from(select.options).forEach(function (opsi) {
                    if (!opsi.value) { return; }

                    const dipakaiBarisLain = terpilih.includes(opsi.value)
                        && select.value !== opsi.value;

                    opsi.disabled = dipakaiBarisLain;
                });
            });

            const hanyaSatu = baris.length <= 1;

            baris.forEach(function (b) {
                b.querySelector('.btn-hapus').disabled = hanyaSatu;
            });
        }

        function hitungTotal() {
            const baris = daftarKue.querySelectorAll('.item-row');
            let total = 0;
            let totalJumlah = 0;
            let jenis = 0;

            baris.forEach(function (b) {
                const harga = ambilHarga(b);
                const jumlah = ambilJumlah(b);
                const subtotal = harga * jumlah;

                b.querySelector('.row-subtotal').textContent = formatRupiah(subtotal);

                if (b.querySelector('.sel-produk').value) {
                    jenis++;
                    totalJumlah += jumlah;
                    total += subtotal;
                }
            });

            jenisTampil.textContent = jenis;
            jumlahTampil.textContent = totalJumlah;
            totalHarga.textContent = formatRupiah(total);
        }

        function segarkan() {
            perbaruiPilihan();
            hitungTotal();
        }

        // Tombol +, -, dan Hapus (satu pendengar untuk semua baris)
        daftarKue.addEventListener('click', function (e) {
            const baris = e.target.closest('.item-row');
            if (!baris) { return; }

            const input = baris.querySelector('.inp-jumlah');

            if (e.target.closest('.btn-plus')) {
                input.value = Math.min(100, (parseInt(input.value, 10) || 0) + 1);
                segarkan();
            } else if (e.target.closest('.btn-minus')) {
                input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
                segarkan();
            } else if (e.target.closest('.btn-hapus')) {
                if (daftarKue.querySelectorAll('.item-row').length > 1) {
                    baris.remove();
                    segarkan();
                }
            }
        });

        daftarKue.addEventListener('change', segarkan);
        daftarKue.addEventListener('input', hitungTotal);

        btnTambah.addEventListener('click', function () {
            const html = templateBaris.innerHTML.replace(/__INDEX__/g, indexBerikut++);
            const wadah = document.createElement('div');
            wadah.innerHTML = html.trim();
            const barisBaru = wadah.firstElementChild;

            daftarKue.appendChild(barisBaru);
            segarkan();

            const select = barisBaru.querySelector('.sel-produk');
            const tersedia = Array.from(select.options).some(function (o) {
                return o.value && !o.disabled;
            });

            if (!tersedia) {
                barisBaru.remove();
                segarkan();
                alert('Semua jenis kue yang tersedia sudah ada di pesanan ini.');
            }
        });

        segarkan();
    </script>
</body>
</html>