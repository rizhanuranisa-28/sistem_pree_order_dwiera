<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #fff9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .register-container {
            width: 100%;
            max-width: 1050px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(180, 110, 130, 0.15);
            background: #fff;
        }

        /* PANEL KIRI */
        .left-section {
            width: 42%;
            background: #fcecef;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 45px 35px;
            text-align: center;
        }

        .logo {
            display: block;
            width: 130px;
            height: 130px;
            object-fit: contain;
            border-radius: 50%;
            margin: 0 auto 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .brand {
            font-family: Georgia, serif;
            font-size: 42px;
            font-style: italic;
            color: #9b596c;
            margin-bottom: 12px;
        }

        .tagline {
            color: #8d6872;
            font-size: 15px;
            line-height: 1.7;
            max-width: 320px;
        }

        /* PANEL KANAN */
        .right-section {
            flex: 1;
            min-width: 0;
            padding: 40px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title {
            font-size: 30px;
            color: #5f4149;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #999;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 13px;
        }

        .form-group label {
            display: block;
            color: #5f4149;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-group input {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 12px 14px;
            border: 1px solid #e5d5d9;
            border-radius: 10px;
            background: #fff;
            outline: none;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #c98699;
            box-shadow: 0 0 0 3px rgba(201, 134, 153, 0.12);
        }

        .register-button {
            display: block;
            width: 100%;
            padding: 14px;
            margin-top: 5px;
            border: none;
            border-radius: 10px;
            background: #c98699;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .register-button:hover {
            background: #b87287;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            line-height: 1.6;
        }

        .login-link a {
            color: #b87287;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message,
        .success-message {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .error-message {
            background: #fff0f2;
            color: #b14f65;
            border: 1px solid #f1c7d0;
        }

        .success-message {
            background: #f0faf3;
            color: #4b8b60;
            border: 1px solid #c9e8d2;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
                align-items: flex-start;
            }

            .register-container {
                max-width: 480px;
                flex-direction: column;
                border-radius: 18px;
            }

            .left-section,
            .right-section {
                width: 100%;
            }

            .left-section {
                padding: 30px 22px;
            }

            .logo {
                width: 95px;
                height: 95px;
                margin-bottom: 15px;
            }

            .brand {
                font-size: 34px;
            }

            .tagline {
                font-size: 14px;
            }

            .right-section {
                padding: 30px 24px;
            }

            .title {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">

        <div class="left-section">
            <img
                src="{{ asset('images/logo_dwiera.jpeg') }}"
                alt="Logo Dwiera"
                class="logo"
            >

            <div class="brand">Dwiera</div>

            <p class="tagline">
                Buat akun untuk melakukan pre-order
                kue ulang tahun dengan lebih mudah.
            </p>
        </div>

        <div class="right-section">
            <h1 class="title">Buat Akun</h1>

            <p class="subtitle">
                Daftarkan akun pelanggan Dwiera
            </p>

            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama_users">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama_users"
                        name="nama_users"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('nama_users') }}"
                        maxlength="100"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="nomor_telepon">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="nomor_telepon"
                        name="nomor_telepon"
                        placeholder="Contoh: 081234567890"
                        value="{{ old('nomor_telepon') }}"
                        maxlength="20"
                        autocomplete="tel"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Contoh: nama@email.com"
                        value="{{ old('email') }}"
                        maxlength="100"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        maxlength="50"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        minlength="6"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        minlength="6"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button type="submit" class="register-button">
                    Daftar
                </button>
            </form>

            <div class="login-link">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>

    </div>
</body>
</html>
