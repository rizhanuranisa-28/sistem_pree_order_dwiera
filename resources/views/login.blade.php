<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Dwiera Cake</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #fffafb;
            color: #333;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
        }

        .left-section {
            width: 50%;
            min-height: 100vh;
            background:
                radial-gradient(circle at 10% 20%, rgba(255,255,255,.45) 0, transparent 28%),
                radial-gradient(circle at 90% 80%, rgba(255,190,205,.45) 0, transparent 30%),
                #fde5ea;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        .left-content {
            width: 100%;
            max-width: 600px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .logo-wrapper {
            width: 220px;
            height: 220px;
            margin: 0 auto 28px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(160,80,100,.15);
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .left-title {
            color: #a94f67;
            font-size: 30px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .left-subtitle {
            color: #a96b7c;
            font-size: 17px;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .decoration {
            position: absolute;
            color: #c76b83;
            opacity: .55;
            font-size: 30px;
        }

        .decoration.one {
            top: 18%;
            left: 12%;
        }

        .decoration.two {
            top: 30%;
            right: 12%;
        }

        .decoration.three {
            bottom: 30%;
            left: 15%;
        }

        .decoration.four {
            bottom: 20%;
            right: 15%;
        }

        .right-section {
            width: 50%;
            min-height: 100vh;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
        }

        .login-box {
            width: 100%;
            max-width: 470px;
        }

        .top-register {
            text-align: right;
            margin-bottom: 65px;
            color: #777;
            font-size: 14px;
        }

        .top-register a {
            color: #d46a8c;
            font-weight: bold;
            text-decoration: none;
        }

        .top-register a:hover,
        .back-link:hover {
            text-decoration: underline;
        }

        .login-heading {
            margin-bottom: 35px;
        }

        .login-heading h1 {
            font-size: 36px;
            color: #252b35;
            margin-bottom: 10px;
        }

        .login-heading p {
            color: #888;
            font-size: 16px;
        }

        .error-message {
            background: #fff0f3;
            color: #c9577c;
            padding: 13px 15px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
            font-weight: bold;
            color: #444;
        }

        .input-wrapper,
        .password-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
            pointer-events: none;
        }

        input {
            width: 100%;
            padding: 15px 16px 15px 48px;
            border: 1px solid #dedede;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
            transition: .2s;
        }

        input:focus {
            border-color: #d46a8c;
            box-shadow: 0 0 0 3px rgba(212,106,140,.08);
        }

        .password-wrapper input {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #999;
            cursor: pointer;
            font-size: 18px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #777;
            margin: 0;
            font-weight: normal;
        }

        .remember input {
            width: 16px;
            height: 16px;
            padding: 0;
            accent-color: #d46a8c;
        }

        .forgot {
            color: #d46a8c;
            text-decoration: none;
            font-weight: bold;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 9px;
            background: #d7658a;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: .2s;
            box-shadow: 0 6px 15px rgba(215,101,138,.2);
        }

        .btn-login:hover {
            background: #c9577c;
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 28px 0;
            color: #999;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e8e8e8;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 28px;
            color: #d46a8c;
            text-decoration: none;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .login-page {
                flex-direction: column;
            }

            .left-section,
            .right-section {
                width: 100%;
                min-height: auto;
                padding: 45px 25px;
            }

            .logo-wrapper {
                width: 170px;
                height: 170px;
            }

            .left-title {
                font-size: 25px;
            }

            .left-subtitle {
                font-size: 15px;
            }

            .top-register {
                margin-bottom: 35px;
            }
        }

        @media (max-width: 500px) {
            .left-section,
            .right-section {
                padding: 35px 20px;
            }

            .logo-wrapper {
                width: 145px;
                height: 145px;
            }

            .login-heading h1 {
                font-size: 29px;
            }

            .top-register {
                text-align: center;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <section class="left-section">

        <div class="decoration one">♡</div>
        <div class="decoration two">♡</div>
        <div class="decoration three">✦</div>
        <div class="decoration four">♡</div>

        <div class="left-content">

            <div class="logo-wrapper">
                <img src="{{ asset('images/logo_dwiera.jpeg') }}"
                     alt="Logo Dwiera">
            </div>

            <h2 class="left-title">
                Kue Ulang Tahun
                <br>
                untuk Momen Spesial
            </h2>

            <p class="left-subtitle">
                Pesan kue ulang tahun favoritmu
                <br>
                dengan mudah melalui Dwiera Cake.
            </p>

        </div>

    </section>

    <section class="right-section">

        <div class="login-box">

            <div class="top-register">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar di sini</a>
            </div>

            <div class="login-heading">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div style="background:#f0faf3;color:#4b8b60;padding:13px 15px;border-radius:10px;margin-bottom:22px;font-size:14px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>

                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="password-wrapper">
                        <span class="input-icon">🔒</span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()"
                        >👁</button>
                    </div>
                </div>

                <div class="form-options">

                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    <!-- TOMBOL LUPA PASSWORD -->
                    <a href="{{ route('password.request') }}" class="forgot">
                        Lupa password?
                    </a>

                </div>

                <button type="submit" class="btn-login">
                    Login →
                </button>

            </form>

            <div class="divider">atau</div>

            <a href="/" class="back-link">
                ← Kembali ke Beranda
            </a>

        </div>

    </section>

</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const button = document.querySelector(".toggle-password");

    if (password.type === "password") {
        password.type = "text";
        button.textContent = "🙈";
    } else {
        password.type = "password";
        button.textContent = "👁";
    }
}
</script>

</body>
</html>