<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Dwiera</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #fff0f5;
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(200, 80, 130, 0.15);
        }

        h2 {
            color: #d64b87;
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            color: #777;
            text-align: center;
            line-height: 1.5;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 22px 0 8px;
            color: #555;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #e5b7cc;
            border-radius: 9px;
            outline: none;
        }

        input:focus {
            border-color: #d64b87;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #d64b87;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #bd3672;
        }

        .back {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #d64b87;
            text-decoration: none;
            font-size: 14px;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            color: #b42318;
            background: #fff0f0;
            border-radius: 8px;
            font-size: 13px;
        }

        .success {
            margin-top: 15px;
            padding: 10px;
            color: #18794e;
            background: #eaf8ef;
            border-radius: 8px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lupa Password?</h2>
    <p>Masukkan email yang terdaftar untuk melanjutkan proses penggantian password akun Dwiera.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label for="email">Alamat Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Masukkan email kamu"
            required
        >

        <button type="submit">Lanjutkan</button>
    </form>

    <a href="{{ route('login') }}" class="back">← Kembali ke Login</a>
</div>

</body>
</html>