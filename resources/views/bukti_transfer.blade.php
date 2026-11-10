<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Bukti Transfer - Dwiera Cake</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }

        body { background: #fff6f8; color: #333; }

        nav {
            background: white; padding: 18px 8%;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        .logo { font-size: 25px; font-weight: bold; color: #d46a8c; }

        .container { max-width: 650px; margin: 40px auto; padding: 0 20px; }

        .card {
            background: white; padding: 30px; border-radius: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        h1 { text-align: center; color: #c9577c; margin-bottom: 10px; }

        .subtitle { text-align: center; color: #777; margin-bottom: 25px; line-height: 1.6; }

        .summary { background: #fff0f4; padding: 18px; border-radius: 12px; margin-bottom: 22px; }

        .row { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 8px; font-size: 14px; }
        .row span { color: #666; }
        .row strong { text-align: right; }

        .total {
            display: flex; justify-content: space-between;
            border-top: 1px solid #e5cfd6; margin-top: 12px; padding-top: 12px;
            font-size: 18px; font-weight: bold; color: #d46a8c;
        }

        .info {
            background: #fff6f8; border: 1px solid #ead8de; border-radius: 10px;
            padding: 14px; font-size: 13px; color: #555; line-height: 1.7; margin-bottom: 20px;
        }

        .account-details {
            margin-top: 12px; padding-top: 12px; border-top: 1px solid #ead8de;
        }

        label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; }

        input[type="file"] {
            width: 100%; padding: 12px; border: 1px solid #e2d2d7;
            border-radius: 8px; background: white; font-size: 14px;
        }

        .hint { font-size: 12px; color: #999; margin-top: 6px; }

        .preview { display: none; max-width: 220px; max-height: 220px; margin: 14px auto 0; border-radius: 10px; border: 1px solid #ead8de; }

        .btn {
            width: 100%; margin-top: 20px; padding: 14px; border: none;
            border-radius: 9px; background: #d46a8c; color: white;
            font-size: 16px; font-weight: bold; cursor: pointer;
        }

        .btn:hover { background: #c9577c; }

        .btn:disabled { opacity: .7; cursor: not-allowed; }

        .later { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: #d46a8c; text-decoration: none; }

        .error {
            background: #fff0f0; border: 1px solid #f1b7b7; color: #a33333;
            padding: 12px 15px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; line-height: 1.6;
        }

        @media (max-width: 600px) {
            nav { padding: 18px 5%; }
            .card { padding: 22px; }
            .container { margin: 25px auto; }
        }
    </style>
</head>
<body>

    <nav><div class="logo">Dwiera Cake</div></nav>

    <div class="container">
        <div class="card">

            <h1>Unggah Bukti Transfer</h1>
            <p class="subtitle">
                Pesananmu sudah tercatat. Satu langkah lagi: unggah foto bukti transfer
                supaya admin bisa memeriksa pembayaranmu.
            </p>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="summary">
                <div class="row">
                    <span>No. Pesanan</span>
                    <strong>#DW{{ str_pad($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</strong>
                </div>

                @foreach ($pesanan->detailPesanans as $detail)
                    <div class="row">
                        <span>{{ $detail->produk->nama_kue ?? 'Produk' }} × {{ $detail->jumlah }}</span>
                        <strong>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                    </div>
                @endforeach

                <div class="total">
                    <span>Total Transfer</span>
                    <span>Rp{{ number_format($pesanan->pembayaran->total_bayar ?? $pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="info">
                Transfer sesuai total di atas ke rekening pembayaran Dwiera,
                lalu foto atau screenshot buktinya dan unggah di bawah.
                <div class="account-details">
                    <strong>Informasi Rekening Dwiera</strong>
                    <div class="row">
                        <span>Bank</span>
                        <strong>{{ config('payment.bank') }}</strong>
                    </div>
                    <div class="row">
                        <span>Atas Nama</span>
                        <strong>{{ config('payment.account_name') }}</strong>
                    </div>
                    <div class="row">
                        <span>Nomor Rekening</span>
                        <strong>{{ config('payment.account_number') }}</strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('pesanan.bukti', $pesanan->id_pesanan) }}"
                  method="POST" enctype="multipart/form-data" id="formBukti">
                @csrf

                <input type="hidden" name="lanjut" value="1">

                <label for="bukti">Foto Bukti Transfer</label>
                <input type="file" id="bukti" name="bukti" accept=".jpg,.jpeg,.png,.webp" required>
                <div class="hint">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</div>

                <img id="preview" class="preview" alt="Pratinjau bukti transfer">

                <button type="submit" class="btn" id="tombol">Unggah dan Selesai</button>
            </form>

            <a href="{{ route('pesanan.pelanggan') }}" class="later">
                Unggah nanti di Pesanan Saya
            </a>

        </div>
    </div>

    <script>
        document.getElementById('bukti').addEventListener('change', function () {
            var file = this.files[0];
            var img = document.getElementById('preview');

            if (file && file.type.indexOf('image/') === 0) {
                img.src = URL.createObjectURL(file);
                img.style.display = 'block';
            } else {
                img.style.display = 'none';
            }
        });

        document.getElementById('formBukti').addEventListener('submit', function () {
            var tombol = document.getElementById('tombol');
            tombol.disabled = true;
            tombol.textContent = 'Mengunggah...';
        });
    </script>

</body>
</html>