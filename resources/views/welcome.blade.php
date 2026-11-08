<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #fff8f8;
            color: #333;
        }

        /* NAVBAR */
        nav {
            background: white;
            padding: 18px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #d46a8c;
        }

        nav a {
            text-decoration: none;
            color: #555;
            margin-left: 25px;
        }

        /* HERO */
        .hero {
            padding: 80px 8%;
            text-align: center;
        }

        .hero h1 {
            font-size: 42px;
            color: #c9577c;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #d46a8c;
            color: white;
            padding: 13px 25px;
            border-radius: 8px;
            text-decoration: none;
        }

        /* KATALOG */
        .section {
            padding: 50px 8%;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #c9577c;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(220px, 1fr)
            );
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .cake {
            height: 150px;
            background: #ffe1ea;
            border-radius: 10px;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 55px;
            margin-bottom: 15px;
        }

        .card h3 {
            margin-bottom: 8px;
        }

        .card p {
            color: #666;
            line-height: 1.5;
        }

        .price {
            color: #d46a8c;
            font-weight: bold;
            margin: 10px 0 15px;
        }

        /* FOOTER */
        footer {
            background: #d46a8c;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        /* RESPONSIVE */
        @media (max-width: 700px) {

            nav {
                flex-direction: column;
                gap: 15px;
            }

            nav a {
                margin: 0 8px;
            }

            .hero h1 {
                font-size: 32px;
            }

            .hero {
                padding: 60px 6%;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>

        <div class="logo">
            Dwiera Cake
        </div>

        <div>
            <a href="#">Beranda</a>
            <a href="#">Katalog</a>
            <a href="#">Pesanan Saya</a>
            <a href="#">Login</a>
        </div>

    </nav>


    <!-- HERO -->
    <section class="hero">

        <h1>
            Kue Ulang Tahun Dwiera
        </h1>

        <p>
            Pesan kue ulang tahun dengan mudah dan praktis.
        </p>

        <a href="#" class="btn">
            Pesan Sekarang
        </a>

    </section>


    <!-- KATALOG -->
    <section class="section">

        <h2>
            Katalog Kue
        </h2>

        <div class="products">


            <!-- PRODUK 1 -->
            <div class="card">

                <div class="cake">
                    🎂
                </div>

                <h3>
                    Chocolate Cake
                </h3>

                <p>
                    Kue cokelat untuk berbagai acara ulang tahun.
                </p>

                <div class="price">
                    Rp150.000
                </div>

                <a href="#" class="btn">
                    Lihat Detail
                </a>

            </div>


            <!-- PRODUK 2 -->
            <div class="card">

                <div class="cake">
                    🍰
                </div>

                <h3>
                    Strawberry Cake
                </h3>

                <p>
                    Kue strawberry dengan rasa yang lembut.
                </p>

                <div class="price">
                    Rp175.000
                </div>

                <a href="#" class="btn">
                    Lihat Detail
                </a>

            </div>


            <!-- PRODUK 3 -->
            <div class="card">

                <div class="cake">
                    🎂
                </div>

                <h3>
                    Birthday Cake
                </h3>

                <p>
                    Kue ulang tahun dengan desain menarik.
                </p>

                <div class="price">
                    Rp200.000
                </div>

                <a href="#" class="btn">
                    Lihat Detail
                </a>

            </div>


        </div>

    </section>


    <!-- FOOTER -->
    <footer>

        © 2026 Dwiera Cake -
        Sistem Pre-Order Kue Ulang Tahun

    </footer>

</body>
</html>