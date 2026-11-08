<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Dwiera</title>

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
        }

        p {
            color: #777;
            text-align: center;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 20px 0 8px;
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

        /* BARU: kolom password dengan tombol mata yang selalu tampil */
        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 48px;
        }

        /* BARU: sembunyikan ikon mata bawaan Edge supaya tidak dobel/hilang-muncul */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }

        .toggle-pass {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #999;
            cursor: pointer;
        }

        .toggle-pass:hover {
            color: #d64b87;
            background: #fff0f5;
        }

        .toggle-pass svg {
            width: 20px;
            height: 20px;
        }

        .toggle-pass .icon-off {
            display: none;
        }

        .toggle-pass.showing .icon-on {
            display: none;
        }

        .toggle-pass.showing .icon-off {
            display: block;
        }

        button.submit {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #d64b87;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button.submit:hover {
            background: #bd3672;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            color: #b42318;
            background: #fff0f0;
            border-radius: 8px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Password Baru</h2>
    <p>Buat password baru untuk akun Dwiera kamu.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('password.update', ['token' => $token]) }}" method="POST">
        @csrf

        <label for="password">Password Baru</label>
        <div class="password-wrap">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 8 karakter"
                minlength="8"
                autocomplete="new-password"
                required
            >
            <button type="button" class="toggle-pass" data-target="password"
                    aria-label="Lihat password">
                <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            </button>
        </div>

        <label for="password_confirmation">Konfirmasi Password</label>
        <div class="password-wrap">
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                minlength="8"
                autocomplete="new-password"
                required
            >
            <button type="button" class="toggle-pass" data-target="password_confirmation"
                    aria-label="Lihat password">
                <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            </button>
        </div>

        <button type="submit" class="submit">Simpan Password Baru</button>
    </form>
</div>

<script>
    document.querySelectorAll('.toggle-pass').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var tampil = input.type === 'password';

            input.type = tampil ? 'text' : 'password';
            btn.classList.toggle('showing', tampil);
            btn.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Lihat password');
        });
    });
</script>

</body>
</html>