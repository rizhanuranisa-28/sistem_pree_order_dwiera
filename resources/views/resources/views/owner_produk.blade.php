<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk | Dwiera</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff5f7;
            color: #333;
        }

        .header {
            background: #c05278;
            color: white;
            padding: 22px 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 25px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            color: #c05278;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .content {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .content h2 {
            margin-top: 0;
            color: #a63d63;
        }

        .info {
            color: #777;
            margin-bottom: 25px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #fce4ec;
            color: #8d3455;
            padding: 15px;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1e4e8;
        }

        tbody tr:hover {
            background: #fff9fb;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: #eeeeee;
            color: #555;
        }

        .status-aktif {
            background: #d9f5e3;
            color: #217746;
        }

        .status-nonaktif {
            background: #ffe0e0;
            color: #a52a2a;
        }

        .empty {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        .total {
            margin-top: 20px;
            color: #777;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .header {
                padding: 20px;
            }

            .container {
                margin-top: 20px;
                padding: 0 12px;
            }

            .content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <h1>Dwiera — Data Produk</h1>
    </header>

    <main class="container">

        <a href="{{ route('owner') }}" class="back-button">
            &larr; Kembali ke Dashboard Owner
        </a>

        <section class="content">
            <h2>Daftar Produk Kue</h2>

            <p class="info">
                Informasi produk kue ulang tahun yang tersimpan dalam sistem.
            </p>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kue</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($produk as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->nama_kue }}
                                </td>

                                <td>
                                    Rp{{ number_format((float) $item->harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if (strtolower((string) $item->status) === 'aktif')
                                        <span class="badge status-aktif">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge status-nonaktif">
                                            {{ ucfirst($item->status ?? 'Tidak diketahui') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">
                                    Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="total">
                Total produk: <strong>{{ $produk->count() }}</strong>
            </p>
        </section>

    </main>

</body>
</html>