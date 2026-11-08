<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - Dwiera Cake</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Courier New", Courier, monospace;
        }

        body {
            background: #f3e9ed;
            color: #111;
            padding: 20px 10px;
        }

        .receipt {
            width: 300px;
            max-width: 100%;
            margin: 0 auto;
            padding: 14px 12px;
            background: #fff;
            font-size: 11px;
            line-height: 1.35;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .center {
            text-align: center;
        }

        /* LOGO DWIERA */
        .receipt-logo {
            display: block;
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin: 0 auto 6px;
        }

        .shop {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .small {
            font-size: 10px;
        }

        /* ALAMAT TOKO */
        .shop-address {
            margin-top: 3px;
            font-size: 9px;
            line-height: 1.3;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 7px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .row span:last-child {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .item-name {
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .item {
            margin-bottom: 4px;
        }

        .total-row {
            font-size: 13px;
            font-weight: bold;
        }

        .note {
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .badge {
            font-weight: bold;
            border: 1px solid #000;
            padding: 0 4px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 4px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin: 16px auto 0;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 6px;
            padding: 9px 14px;
            background: #e9a0b5;
            color: #fff;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #d987a0;
        }

        /* UKURAN KERTAS PDF */
        @page {
            size: 80mm 180mm;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff;
            }

            body {
                color: #111;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .receipt {
                width: 80mm !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 4mm !important;
                background: #fff;
                box-shadow: none !important;

                /* Pertahankan ukuran font dan jarak */
                font-size: 11px;
                line-height: 1.35;
            }

            .receipt-logo {
                width: 75px;
                height: 75px;
                object-fit: contain;
            }

            .shop {
                font-size: 15px;
            }

            .small {
                font-size: 10px;
            }

            .shop-address {
                font-size: 9px;
                line-height: 1.3;
            }

            .total-row {
                font-size: 13px;
            }

            .footer {
                font-size: 10px;
            }

            .actions {
                display: none !important;
            }
        }
    </style>
</head>

<body>

@php
    $pembayaran = $pesanan->pembayaran;

    $totalTagihan = (float) (
        $pembayaran->total_bayar
        ?? $pesanan->total_harga
        ?? 0
    );

    $lunas = $pembayaran
        && !empty($pembayaran->tanggal_bayar)
        && (float) ($pembayaran->bayar ?? 0) >= $totalTagihan
        && $totalTagihan > 0;

    $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');

    $tanggalAmbil = $pesanan->jadwalPengambilan->tanggal_pengambilan
        ?? $pesanan->tanggal_pengambilan
        ?? null;
@endphp

<div class="receipt">

    <!-- HEADER STRUK DAN LOGO DWIERA -->
    <div class="center">

        <img
            src="{{ asset('images/logo_dwiera.jpeg') }}"
            alt="Logo Dwiera Cake"
            class="receipt-logo"
        >

        <div class="shop">DWIERA CAKE</div>
        <div class="small">Kue Ulang Tahun Pre-Order</div>

        <!-- ALAMAT TOKO -->
        <div class="shop-address">
            Jl. Babakan Tiga, Panyocokan,<br>
            Kec. Ciwidey, Kabupaten Bandung,<br>
            Jawa Barat 40971
        </div>

    </div>

    <div class="line"></div>

    <div class="row">
        <span>No</span>
        <span>#DW{{ str_pad($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="row">
        <span>Tgl</span>
        <span>
            {{ $pesanan->tanggal_pesan
                ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d/m/Y H:i')
                : '-' }}
        </span>
    </div>

    <div class="row">
        <span>Nama</span>
        <span>{{ $pesanan->user->nama_users ?? '-' }}</span>
    </div>

    <div class="row">
        <span>Telp</span>
        <span>{{ $pesanan->user->nomor_telepon ?? '-' }}</span>
    </div>

    <div class="line"></div>

    @foreach($pesanan->detailPesanans as $detail)
        <div class="item">
            <div class="item-name">
                {{ $detail->produk->nama_kue ?? 'Produk tidak ditemukan' }}
            </div>

            <div class="row">
                <span>
                    {{ $detail->jumlah }} x
                    {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                </span>

                <span>
                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                </span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="row total-row">
        <span>TOTAL</span>
        <span>{{ $rp($totalTagihan) }}</span>
    </div>

    @if($pembayaran && $lunas && (float) $pembayaran->bayar > 0)
        <div class="row">
            <span>Bayar</span>
            <span>{{ $rp($pembayaran->bayar) }}</span>
        </div>

        @if((float) ($pembayaran->kembalian ?? 0) > 0)
            <div class="row">
                <span>Kembali</span>
                <span>{{ $rp($pembayaran->kembalian) }}</span>
            </div>
        @endif
    @endif

    <div class="line"></div>

    <div class="row">
        <span>Metode</span>
        <span>{{ ucfirst($pembayaran->metode_pembayaran ?? '-') }}</span>
    </div>

    <div class="row">
        <span>Status</span>
        <span>
            @if($lunas)
                <span class="badge">LUNAS</span>
            @else
                <span class="badge">BELUM LUNAS</span>
            @endif
        </span>
    </div>

    @if($pembayaran && $pembayaran->tanggal_bayar)
        <div class="row">
            <span>Dibayar</span>
            <span>
                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
            </span>
        </div>
    @endif

    <div class="row">
        <span>Pesanan</span>
        <span>
            {{ ucfirst(str_replace('_', ' ', $pesanan->status_pesanan)) }}
        </span>
    </div>

    <div class="line"></div>

    <div class="row">
        <span>Ambil</span>
        <span>
            {{ $tanggalAmbil
                ? \Carbon\Carbon::parse($tanggalAmbil)->format('d/m/Y')
                : '-' }}
            {{ substr((string) ($pesanan->jadwalPengambilan->jam_pengambilan ?? ''), 0, 5) }}
        </span>
    </div>

    @if (!empty($pesanan->catatan))
        <div class="line"></div>

        <div><strong>Catatan:</strong></div>

        <div class="note">{{ $pesanan->catatan }}</div>
    @endif

    <div class="line"></div>

    <div class="footer">
        Terima kasih telah memesan<br>
        di Dwiera Cake<br>
        Struk ini bukti rincian pesanan.
    </div>

</div>

<div class="actions">

    <button class="btn" onclick="window.print()">
        Cetak / Simpan PDF
    </button>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.pesanan') }}" class="btn">
            Kembali ke Pesanan
        </a>
    @else
        <a href="{{ route('pesanan.pelanggan') }}" class="btn">
            Kembali ke Pesanan Saya
        </a>
    @endif

</div>

</body>
</html>

