<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan Berhasil - Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff6f8;
            color: #333;
        }

        nav {
            background: white;
            padding: 18px 8%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #d46a8c;
        }

        .container {
            max-width: 650px;
            margin: 70px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 45px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: #ffe1ea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            color: #d46a8c;
        }

        h1 {
            color: #c9577c;
            margin-bottom: 12px;
        }

        .message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .status {
            background: #fff0f4;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            color: #555;
            line-height: 1.6;
        }

        .status strong {
            color: #d46a8c;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 14px;
            border-radius: 9px;
            background: #d46a8c;
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .btn:hover {
            background: #c9577c;
        }

        @media (max-width: 600px) {
            nav {
                padding: 18px 5%;
            }

            .container {
                margin: 40px auto;
            }

            .card {
                padding: 35px 22px;
            }
        }
    </style>
</head>

<body>

    <nav>

        <div class="logo">
            Dwiera Cake
        </div>

    </nav>

    <div class="container">

        <div class="card">

            <div class="icon">
                ✓
            </div>

            <h1>
                Pesanan Berhasil!
            </h1>

            <p class="message">
                Terima kasih telah melakukan pemesanan
                di Dwiera Cake.
                Pesanan kamu telah diterima oleh sistem.
            </p>

            <div class="status">

                Status Pesanan:
                <strong>
                    Menunggu Konfirmasi
                </strong>

                <br><br>

                Admin akan memeriksa pesanan dan pembayaran
                kamu sebelum pesanan diproses.

            </div>

            <a
                href="{{ route('pelanggan') }}"
                class="btn"
            >
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</body>

</html>
