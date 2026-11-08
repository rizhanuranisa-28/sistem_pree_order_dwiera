<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan | Dwiera Cake</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #fff5f8;
            color: #49313b;
        }

        .container {
            max-width: 1000px;
            width: 100%;
            margin: auto;
        }

        .header { margin-bottom: 24px; }
        .header h1 { color: #d94683; margin-bottom: 8px; }
        .header p { color: #806773; margin-top: 0; }

        .card {
            background: white;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(180,80,120,.09);
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            color: #c93676;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: 18px;
        }

        .field { margin-bottom: 18px; min-width: 0; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 15px;
        }

        input, select, textarea {
            width: 100%;
            min-width: 0;
            padding: 12px;
            border: 1px solid #f0c5d7;
            border-radius: 10px;
            font: inherit;
            background: #fff;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #f7b4d0;
            border-color: #d94683;
        }

        textarea { min-height: 100px; resize: vertical; }

        .product-row {
            display: grid;
            grid-template-columns: minmax(0,2fr) minmax(0,1.2fr) 100px;
            gap: 14px;
            align-items: start;
            padding: 18px;
            border: 1px solid #f5d6e3;
            border-radius: 12px;
            margin-bottom: 14px;
            background: #fffafd;
        }

        .product-row .field { margin: 0; min-width: 0; }

        .product-row label {
            height: 18px;
            line-height: 18px;
            margin: 0 0 10px;
        }

        .product-row select,
        .product-row input {
            height: 48px;
        }

        .price-info, .hint {
            color: #856a76;
            font-size: 12px;
            margin-top: 8px;
            line-height: 1.5;
        }

        .product-remove {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 28px;
        }

        .product-remove button {
            width: 100%;
            height: 48px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-family: inherit;
        }

        .btn-primary { background: #d94683; color: white; }
        .btn-primary:hover { background: #bd306b; }
        .btn-secondary { background: #fce4ee; color: #b92e68; }
        .btn-danger { background: #ffe6e6; color: #c62828; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .total-box {
            background: #fff0f6;
            border: 1px solid #f5c3d8;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .total-label { color: #856a76; margin-bottom: 8px; }

        .total-value {
            color: #c93676;
            font-size: 27px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fff0f0;
            color: #b42318;
            border: 1px solid #f5c2c2;
        }

        .error-list { padding-left: 20px; margin-bottom: 0; }
        .hidden { display: none !important; }

        @media (max-width: 650px) {
            body { padding: 20px 12px; }
            .grid { grid-template-columns: 1fr; gap: 0; }
            .card { padding: 20px 16px; }

            .product-row {
                grid-template-columns: minmax(0,1fr) minmax(80px,.65fr);
                gap: 12px;
                padding: 14px;
            }

            .product-row .product-select,
            .product-row .product-size,
            .product-remove {
                grid-column: 1 / -1;
            }

            .product-remove { padding: 0; }
            .product-remove button { height: 44px; }
            .total-value { font-size: 23px; }
            .actions .btn { flex: 1; }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <h1>🎂 Tambah Pesanan</h1>
        <p>Dwiera Cake — Input pesanan customer yang datang langsung ke toko.</p>
    </div>

    <a href="{{ route('admin.pesanan') }}"
       class="btn btn-secondary"
       style="margin-bottom:20px;">
        ← Kembali ke Kelola Pesanan
    </a>

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

    <form action="{{ route('admin.pesanan.store') }}"
          method="POST"
          id="formPesanan">
        @csrf

        <div class="card">
            <h2>1. Data Customer</h2>

            <div class="grid">
                <div class="field">
                    <label for="nama_customer">Nama Customer *</label>
                    <input type="text" id="nama_customer"
                           name="nama_customer"
                           value="{{ old('nama_customer') }}"
                           maxlength="100" required>
                </div>

                <div class="field">
                    <label for="nomor_telepon">Nomor Telepon *</label>
                    <input type="tel" id="nomor_telepon"
                           name="nomor_telepon"
                           value="{{ old('nomor_telepon') }}"
                           maxlength="20" required>
                    <div class="hint">
                        Nomor telepon digunakan untuk mencari ID Customer lama.
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>2. Pilih Produk Kue</h2>

            <div id="daftarProduk"></div>

            <button type="button" class="btn btn-secondary"
                    id="tambahProduk">
                + Tambah Kue
            </button>

            <div class="total-box">
                <div class="total-label">Total Harga Pesanan</div>
                <div class="total-value" id="totalHarga">Rp0</div>
                <div class="hint">
                    Total dihitung berdasarkan ukuran dan jumlah kue.
                </div>
            </div>
        </div>

        <div class="card">
            <h2>3. Jadwal Pengambilan</h2>

            <div class="grid">
                <div class="field">
                    <label for="tanggal_pengambilan">Tanggal Pengambilan *</label>
                    <input type="date" id="tanggal_pengambilan"
                           name="tanggal_pengambilan"
                           value="{{ old('tanggal_pengambilan') }}"
                           min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="field">
                    <label for="jam_pengambilan">Jam Pengambilan *</label>
                    <input type="time" id="jam_pengambilan"
                           name="jam_pengambilan"
                           value="{{ old('jam_pengambilan') }}" required>
                </div>
            </div>

            <div class="field">
                <label for="catatan">Catatan Khusus Kue</label>
                <textarea id="catatan" name="catatan"
                          maxlength="1000"
                          placeholder="Contoh: Happy Birthday, Aulia!">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="card">
            <h2>4. Pembayaran</h2>

            <div class="field">
                <label for="metode_pembayaran">Metode Pembayaran *</label>
                <select id="metode_pembayaran"
                        name="metode_pembayaran" required>
                    <option value="">-- Pilih Pembayaran --</option>
                    <option value="cash"
                        {{ old('metode_pembayaran') === 'cash' ? 'selected' : '' }}>
                        Cash / Tunai
                    </option>
                    <option value="transfer"
                        {{ old('metode_pembayaran') === 'transfer' ? 'selected' : '' }}>
                        Transfer Bank
                    </option>
                </select>
            </div>

            <div id="bagianCash" class="hidden">
                <div class="field">
                    <label for="jumlah_bayar">Uang Diterima (Rp) *</label>
                    <input type="number" id="jumlah_bayar"
                           name="jumlah_bayar"
                           min="0" step="1"
                           value="{{ old('jumlah_bayar') }}">
                </div>

                <div class="total-box">
                    <div class="total-label">Perkiraan Kembalian</div>
                    <div class="total-value" id="kembalian">Rp0</div>
                </div>
            </div>

            <div id="bagianTransfer" class="hidden">
                <p class="hint">
                    Pembayaran transfer harus dilengkapi bukti pembayaran
                    dan diverifikasi admin melalui alur pembayaran yang tersedia.
                </p>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">
                Simpan Pesanan
            </button>

            <a href="{{ route('admin.pesanan') }}"
               class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const produkTersedia = {{ Illuminate\Support\Js::from(
        $produk->map(function ($item) {
            return [
                'id' => $item->id_produk,
                'nama' => $item->nama_kue,
                'ukuran' => $item->ukuranProduks->map(function ($ukuran) {
                    return [
                        'id' => $ukuran->id_ukuran,
                        'nama' => $ukuran->nama_ukuran,
                        'harga' => (float) $ukuran->harga,
                    ];
                })->values()->all(),
            ];
        })->values()->all()
    ) }};

    const daftarProduk = document.getElementById('daftarProduk');
    const metode = document.getElementById('metode_pembayaran');
    const inputBayar = document.getElementById('jumlah_bayar');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(angka || 0);
    }

    function buatBarisProduk(idPilihan = '', idUkuran = '', jumlahAwal = 1) {
        const index = daftarProduk.children.length;
        const baris = document.createElement('div');

        baris.className = 'product-row';

        const pilihan = produkTersedia.map(p => `
            <option value="${p.id}"
                ${String(p.id) === String(idPilihan) ? 'selected' : ''}>
                ${p.nama}
            </option>
        `).join('');

        baris.innerHTML = `
            <div class="field product-select">
                <label>Produk Kue *</label>
                <select name="produk[${index}][id_produk]"
                        class="pilihan-produk" required>
                    <option value="">-- Pilih Kue --</option>
                    ${pilihan}
                </select>
                <div class="price-info harga-produk">Pilih produk</div>
            </div>

            <div class="field product-size">
                <label>Ukuran *</label>
                <select name="produk[${index}][id_ukuran]"
                        class="pilihan-ukuran" required>
                    <option value="">-- Pilih Ukuran --</option>
                </select>
                <div class="price-info harga-ukuran">Pilih ukuran</div>
            </div>

            <div class="field">
                <label>Jumlah *</label>
                <input type="number"
                       name="produk[${index}][jumlah]"
                       class="jumlah-produk"
                       min="1" max="100"
                       value="${jumlahAwal}" required>
            </div>

            <div class="product-remove">
                <button type="button"
                        class="btn btn-danger hapus-produk">
                    Hapus
                </button>
            </div>
        `;

        daftarProduk.appendChild(baris);

        const pilihProduk = baris.querySelector('.pilihan-produk');
        const pilihUkuran = baris.querySelector('.pilihan-ukuran');

        function isiUkuran(idUkuranDipilih = '') {
            const produk = produkTersedia.find(
                p => String(p.id) === String(pilihProduk.value)
            );

            pilihUkuran.innerHTML =
                '<option value="">-- Pilih Ukuran --</option>';

            if (!produk || produk.ukuran.length === 0) {
                baris.querySelector('.harga-ukuran').textContent =
                    'Belum ada ukuran aktif untuk produk ini.';
                hitungTotal();
                return;
            }

            produk.ukuran.forEach(u => {
                const option = document.createElement('option');
                option.value = u.id;
                option.textContent = u.nama + ' — ' + formatRupiah(u.harga);
                option.selected = String(u.id) === String(idUkuranDipilih);
                pilihUkuran.appendChild(option);
            });

            hitungTotal();
        }

        pilihProduk.addEventListener('change', function () {
            isiUkuran();
        });

        pilihUkuran.addEventListener('change', hitungTotal);

        baris.querySelector('.jumlah-produk')
            .addEventListener('input', hitungTotal);

        baris.querySelector('.hapus-produk')
            .addEventListener('click', function () {
                baris.remove();
                perbaruiNamaInput();
                hitungTotal();
            });

        if (idPilihan) {
            pilihProduk.value = idPilihan;
        }

        isiUkuran(idUkuran);
        hitungTotal();
    }

    function perbaruiNamaInput() {
        [...daftarProduk.children].forEach((baris, index) => {
            baris.querySelector('.pilihan-produk').name =
                `produk[${index}][id_produk]`;

            baris.querySelector('.pilihan-ukuran').name =
                `produk[${index}][id_ukuran]`;

            baris.querySelector('.jumlah-produk').name =
                `produk[${index}][jumlah]`;
        });
    }

    function hitungTotal() {
        let total = 0;

        [...daftarProduk.children].forEach(baris => {
            const idProduk = baris.querySelector('.pilihan-produk').value;
            const idUkuran = baris.querySelector('.pilihan-ukuran').value;
            const jumlah = Math.max(
                0,
                Number(baris.querySelector('.jumlah-produk').value) || 0
            );

            const produk = produkTersedia.find(
                p => String(p.id) === String(idProduk)
            );

            const ukuran = produk?.ukuran.find(
                u => String(u.id) === String(idUkuran)
            );

            const harga = ukuran ? Number(ukuran.harga) : 0;

            total += harga * jumlah;

            baris.querySelector('.harga-produk').textContent =
                produk ? 'Produk: ' + produk.nama : 'Pilih produk';

            baris.querySelector('.harga-ukuran').textContent =
                ukuran ? 'Harga satuan: ' + formatRupiah(harga) : 'Pilih ukuran';
        });

        document.getElementById('totalHarga').textContent =
            formatRupiah(total);

        const uang = Number(inputBayar.value) || 0;

        document.getElementById('kembalian').textContent =
            formatRupiah(Math.max(0, uang - total));
    }

    function aturMetodePembayaran() {
        const cash = metode.value === 'cash';
        const transfer = metode.value === 'transfer';

        document.getElementById('bagianCash')
            .classList.toggle('hidden', !cash);

        document.getElementById('bagianTransfer')
            .classList.toggle('hidden', !transfer);

        inputBayar.required = cash;

        if (!cash) {
            inputBayar.value = '';
        }

        hitungTotal();
    }

    document.getElementById('tambahProduk')
        .addEventListener('click', function () {
            buatBarisProduk();
        });

    metode.addEventListener('change', aturMetodePembayaran);
    inputBayar.addEventListener('input', hitungTotal);

    const produkLama = {{ Illuminate\Support\Js::from(old('produk', [])) }};

    if (produkLama.length > 0) {
        produkLama.forEach(item => {
            buatBarisProduk(
                item.id_produk || '',
                item.id_ukuran || '',
                item.jumlah || 1
            );
        });
    } else if (produkTersedia.length > 0) {
        buatBarisProduk();
    }

    aturMetodePembayaran();
</script>

</body>
</html>