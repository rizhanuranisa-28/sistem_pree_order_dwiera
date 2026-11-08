<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pelanggan - Dwiera Cake</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #fff5f8;
            color: #51333e;
        }

        .container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #c34e79;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(180, 100, 130, 0.10);
        }

        h1 {
            margin-top: 0;
            color: #bd5079;
        }

        .subtitle {
            color: #927783;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .avatar {
            width: 85px;
            height: 85px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #ffe0eb;
            font-size: 38px;
        }

        .account-info {
            padding: 14px;
            margin-bottom: 24px;
            background: #fff5f8;
            border-radius: 10px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input {
            display: block;
            width: 100%;
            min-height: 46px;
            padding: 13px;
            border: 1px solid #edcfda;
            border-radius: 10px;
            font-size: 16px;
        }

        input:focus {
            outline: 2px solid #f4b5cd;
            border-color: #d66d97;
        }

        button {
            width: 100%;
            min-height: 46px;
            margin-top: 25px;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #d96f99;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #c65382;
        }

        .success {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 10px;
            background: #e4f8e9;
            color: #27683a;
        }

        .error {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 10px;
            background: #fff0f0;
            color: #c62828;
            font-size: 14px;
        }

        .field-error {
            margin-top: 6px;
            color: #c62828;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <a href="{{ route('pelanggan') }}" class="back">
        ← Kembali ke Beranda
    </a>

    <div class="card">
        <div class="avatar">👤</div>

        <h1>Profil Saya</h1>

        <p class="subtitle">
            Kelola informasi akunmu di Dwiera Cake 🎂
        </p>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                <strong>Periksa kembali data yang kamu masukkan:</strong>
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="account-info">
            <strong>ID Pengguna:</strong>
            {{ $user->id_users }}

            <br>

            <strong>Role:</strong>
            {{ ucfirst($user->role ?? '-') }}
        </div>

        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nama_users">Nama Lengkap</label>
            <input
                type="text"
                id="nama_users"
                name="nama_users"
                value="{{ old('nama_users', $user->nama_users) }}"
                maxlength="100"
                required
            >

            @error('nama_users')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username', $user->username) }}"
                maxlength="50"
                required
            >

            @error('username')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>

</div>
</body>
</html>