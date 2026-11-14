<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - Dwiera</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }

        body { background: #fff7f9; color: #333; }

        .sidebar {
            position: fixed; left: 0; top: 0;
            width: 245px; height: 100vh;
            background: #fff; padding: 30px 20px;
            box-shadow: 3px 0 18px rgba(0,0,0,.06);
            z-index: 10;
        }

        .logo {
            text-align: center; color: #d46a8c;
            font-size: 27px; font-weight: bold; margin-bottom: 40px;
        }

        .menu { display: flex; flex-direction: column; gap: 8px; }

        .menu a {
            display: flex; align-items: center;
            padding: 14px 16px; border-radius: 10px;
            text-decoration: none; color: #666;
            font-size: 14px; transition: .2s;
        }

        .menu a:hover { background: #fff0f4; color: #d46a8c; }
        .menu a.active { background: #f8dce6; color: #c9577c; font-weight: bold; }

        .logout { position: absolute; bottom: 25px; left: 20px; right: 20px; }

        .logout button {
            width: 100%; padding: 12px; border: none; border-radius: 9px;
            background: #d46a8c; color: white; font-weight: bold; cursor: pointer;
        }

        .logout button:hover { background: #c9577c; }

        .mobile-bar {
            display: none;
            position: fixed; top: 0; left: 0; right: 0;
            height: 60px; padding: 0 16px;
            background: #fff; border-bottom: 1px solid #f1dfe5;
            align-items: center; justify-content: space-between;
            z-index: 50;
        }

        .mobile-logo { font-size: 24px; font-weight: bold; color: #d46a8c; }

        .dots-wrap { position: relative; }

        .dots-btn {
            width: 42px; height: 42px; border: none; border-radius: 10px;
            background: #fff0f4; color: #c9577c;
            font-size: 24px; font-weight: bold; line-height: 1; cursor: pointer;
        }

        .dots-menu {
            display: none; position: absolute; top: 50px; right: 0;
            width: 220px; padding: 8px; background: #fff;
            border: 1px solid #f1dfe5; border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,.12);
        }

        .dots-menu.open { display: block; }

        .dots-menu a,
        .dots-menu button {
            width: 100%; display: block; padding: 12px;
            border: none; background: transparent; border-radius: 8px;
            text-decoration: none; color: #666; font-size: 14px;
            text-align: left; cursor: pointer;
        }

        .dots-menu a:hover,
        .dots-menu button:hover { background: #fff0f4; color: #d46a8c; }

        .dots-menu a.active { background: #f8dce6; color: #c9577c; font-weight: bold; }

        .dots-menu hr { border: none; border-top: 1px solid #f4e8ec; margin: 6px 0; }

        .main { margin-left: 245px; padding: 35px 40px; }

        .topbar {
            display: flex; justify-content: space-between;
            align-items: center; gap: 15px; margin-bottom: 30px;
        }

        .title h1 { color: #c9577c; font-size: 28px; margin-bottom: 7px; }
        .title p { color: #888; font-size: 14px; }

        .owner-badge {
            background: white; padding: 10px 18px; border-radius: 25px;
            color: #c9577c; font-weight: bold;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr);
            gap: 22px; align-items: start;
        }

        .panel {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06); min-width: 0;
        }

        .panel h2 { color: #c9577c; font-size: 19px; margin-bottom: 18px; }

        .form-group { margin-bottom: 14px; }

        label {
            display: block; margin-bottom: 6px;
            font-size: 13px; font-weight: bold; color: #555;
        }

        input {
            width: 100%; padding: 11px 12px;
            border: 1px solid #ecc9d6; border-radius: 9px;
            font-size: 14px; color: #444; outline: none;
        }

        input:focus { border-color: #d46a8c; }

        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 46px; }

        .toggle-pass {
            position: absolute; top: 50%; right: 8px;
            transform: translateY(-50%);
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent;
            color: #999; cursor: pointer; border-radius: 8px;
        }

        .toggle-pass:hover { color: #d46a8c; background: #fff0f4; }

        .toggle-pass svg { width: 20px; height: 20px; }
        .toggle-pass .icon-off { display: none; }
        .toggle-pass.showing .icon-on { display: none; }
        .toggle-pass.showing .icon-off { display: block; }

        .btn {
            width: 100%; padding: 12px; border: none; border-radius: 9px;
            background: #d46a8c; color: white; font-weight: bold;
            font-size: 14px; cursor: pointer;
        }

        .btn:hover { background: #c9577c; }

        .aksi { display: flex; gap: 6px; flex-wrap: wrap; }

        .btn-reset {
            padding: 7px 12px; border: none; border-radius: 7px;
            background: #e8efff; color: #3a5bb2;
            font-weight: bold; font-size: 12px; cursor: pointer;
        }

        .btn-reset:hover { background: #d6e2ff; }

        .btn-toggle {
            padding: 7px 12px; border: none; border-radius: 7px;
            font-weight: bold; font-size: 12px; cursor: pointer;
        }

        .btn-nonaktif {
            background: #fde8ec; color: #b23a56;
        }

        .btn-nonaktif:hover { background: #f9d3db; }

        .btn-aktif {
            background: #e8f8ee; color: #216b43;
        }

        .btn-aktif:hover { background: #d3f0df; }

        .status-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-aktif {
            background: #e8f8ee;
            color: #216b43;
        }

        .status-nonaktif {
            background: #fde8ec;
            color: #b23a56;
        }

        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.4); z-index: 100;
            align-items: center; justify-content: center; padding: 15px;
        }

        .modal-overlay.open { display: flex; }

        .modal {
            width: 100%; max-width: 420px; background: white;
            border-radius: 15px; padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,.2);
        }

        .modal h3 { color: #c9577c; font-size: 18px; margin-bottom: 6px; }

        .modal .modal-sub {
            color: #888; font-size: 13px;
            margin-bottom: 18px; line-height: 1.5;
        }

        .modal-actions { display: flex; gap: 10px; margin-top: 18px; }

        .btn-cancel {
            flex: 1; padding: 12px; border: none; border-radius: 9px;
            background: #f3f3f3; color: #666; font-weight: bold;
            font-size: 14px; cursor: pointer;
        }

        .btn-cancel:hover { background: #e8e8e8; }
        .modal-actions .btn { flex: 1; width: auto; }

        .alert {
            padding: 12px 15px; border-radius: 9px;
            margin-bottom: 18px; font-size: 13px; line-height: 1.5;
        }

        .alert-success { background: #e8f8ee; color: #216b43; }
        .alert-error { background: #fff0f0; color: #a52a2a; }
        .alert ul { margin: 5px 0 0 18px; }

        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; min-width: 650px; }

        th {
            background: #f8dce6; color: #c9577c;
            padding: 12px; text-align: left; font-size: 13px;
        }

        td {
            padding: 12px; border-bottom: 1px solid #f4e8ec;
            font-size: 13px; vertical-align: middle;
        }

        .empty { text-align: center; color: #999; padding: 30px; font-size: 14px; }

        @media (max-width: 1000px) {
            .grid { grid-template-columns: minmax(0, 1fr); }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .mobile-bar { display: flex; }
            .main { margin-left: 0; padding: 80px 15px 22px; }
            .topbar { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>

<body>
    @include('partials.mobile_bottom_nav')

    <div class="mobile-bar">
        <div class="mobile-logo">Dwiera</div>

        <div class="dots-wrap">
            <button type="button" class="dots-btn" id="dotsBtn"
                    aria-label="Buka menu" aria-expanded="false">⋮</button>

            <div class="dots-menu" id="dotsMenu">
                <a href="{{ route('owner') }}">Dashboard</a>
                <a href="{{ route('owner.pesanan') }}">Data Pesanan</a>
                <a href="{{ route('owner.produk') }}">Data Produk</a>
                <a href="{{ route('owner.laporan') }}">Laporan Penjualan</a>
                <a href="{{ route('owner.admin') }}" class="active">Kelola Admin</a>
                <hr>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="logo">Dwiera</div>

        <nav class="menu">
            <a href="{{ route('owner') }}">Dashboard</a>
            <a href="{{ route('owner.pesanan') }}">Data Pesanan</a>
            <a href="{{ route('owner.produk') }}">Data Produk</a>
            <a href="{{ route('owner.laporan') }}">Laporan Penjualan</a>
            <a href="{{ route('owner.admin') }}" class="active">Kelola Admin</a>
        </nav>

        <form class="logout" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </aside>

    <main class="main">

        <header class="topbar">
            <div class="title">
                <h1>Kelola Admin</h1>
                <p>Owner dapat membuat, mengatur, dan menonaktifkan akun admin</p>
            </div>

            <div class="owner-badge">Owner</div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid">

            <div class="panel">
                <h2>Tambah Akun Admin</h2>

                <form action="{{ route('owner.admin.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="form-group">
                        <label for="nama_users">Nama Lengkap</label>
                        <input type="text" id="nama_users" name="nama_users"
                               value="{{ old('nama_users') }}" maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon"
                               value="{{ old('nomor_telepon') }}" maxlength="20" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}" maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"
                               value="{{ old('username') }}" maxlength="50"
                               autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password"
                                   minlength="6" autocomplete="new-password" required>

                            <button type="button" class="toggle-pass" data-target="password"
                                    aria-label="Lihat password">
                                <svg class="icon-on" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-off" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password_confirmation"
                                   name="password_confirmation" minlength="6"
                                   autocomplete="new-password" required>

                            <button type="button" class="toggle-pass"
                                    data-target="password_confirmation"
                                    aria-label="Lihat password">
                                <svg class="icon-on" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-off" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn">Buat Akun Admin</button>
                </form>
            </div>

            <div class="panel">
                <h2>Daftar Admin</h2>

                @if ($admins->count() > 0)
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($admins as $admin)
                                    @php
                                        $statusAdmin = $admin->status ?? 'aktif';
                                    @endphp

                                    <tr>
                                        <td>{{ $admin->nama_users }}</td>
                                        <td>{{ $admin->username }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->nomor_telepon }}</td>

                                        <td>
                                            <span class="status-badge {{ $statusAdmin === 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                                {{ ucfirst($statusAdmin) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="aksi">

                                                <button type="button" class="btn-reset"
                                                        data-id="{{ $admin->id_users }}"
                                                        data-nama="{{ $admin->nama_users }}">
                                                    Reset Password
                                                </button>

                                                <form action="{{ route('owner.admin.status', $admin->id_users) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin {{ $statusAdmin === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $admin->nama_users }}?');">

                                                    @csrf
                                                    @method('PATCH')

                                                    @if ($statusAdmin === 'aktif')
                                                        <button type="submit" class="btn-toggle btn-nonaktif">
                                                            Nonaktifkan
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn-toggle btn-aktif">
                                                            Aktifkan
                                                        </button>
                                                    @endif

                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty">Belum ada akun admin.</div>
                @endif
            </div>

        </section>

    </main>

    <div class="modal-overlay" id="resetModal">
        <div class="modal">
            <h3>Reset Password Admin</h3>

            <p class="modal-sub">
                Buat password baru untuk <strong id="resetNama"></strong>.
                Password lama langsung tidak berlaku.
            </p>

            <form id="resetForm" method="POST" action="" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="reset_password">Password Baru</label>

                    <div class="password-wrap">
                        <input type="password" id="reset_password" name="password"
                               minlength="6" autocomplete="new-password" required>

                        <button type="button" class="toggle-pass"
                                data-target="reset_password"
                                aria-label="Lihat password">
                            <svg class="icon-on" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-off" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reset_password_confirmation">Konfirmasi Password Baru</label>

                    <div class="password-wrap">
                        <input type="password" id="reset_password_confirmation"
                               name="password_confirmation" minlength="6"
                               autocomplete="new-password" required>

                        <button type="button" class="toggle-pass"
                                data-target="reset_password_confirmation"
                                aria-label="Lihat password">
                            <svg class="icon-on" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-off" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="resetBatal">Batal</button>
                    <button type="submit" class="btn">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tombol mata untuk melihat password
        document.querySelectorAll('.toggle-pass').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.dataset.target);
                var tampil = input.type === 'password';

                input.type = tampil ? 'text' : 'password';
                btn.classList.toggle('showing', tampil);

                btn.setAttribute(
                    'aria-label',
                    tampil ? 'Sembunyikan password' : 'Lihat password'
                );
            });
        });

        // Popup reset password
        (function () {
            var modal = document.getElementById('resetModal');
            var form = document.getElementById('resetForm');
            var namaEl = document.getElementById('resetNama');
            var urlTemplate = "{{ route('owner.admin.password', ':id') }}";

            function tutup() {
                modal.classList.remove('open');
                form.reset();

                modal.querySelectorAll('.password-wrap input').forEach(function (input) {
                    input.type = 'password';
                });

                modal.querySelectorAll('.toggle-pass').forEach(function (btn) {
                    btn.classList.remove('showing');
                });
            }

            document.querySelectorAll('.btn-reset').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    form.action = urlTemplate.replace(':id', btn.dataset.id);
                    namaEl.textContent = btn.dataset.nama;

                    modal.classList.add('open');
                    document.getElementById('reset_password').focus();
                });
            });

            document.getElementById('resetBatal').addEventListener('click', tutup);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    tutup();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    tutup();
                }
            });
        })();

        // Menu titik tiga untuk tampilan HP
        (function () {
            var tombol = document.getElementById('dotsBtn');
            var menu = document.getElementById('dotsMenu');

            function tutup() {
                menu.classList.remove('open');
                tombol.setAttribute('aria-expanded', 'false');
            }

            tombol.addEventListener('click', function (e) {
                e.stopPropagation();

                var buka = menu.classList.toggle('open');
                tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) {
                    tutup();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    tutup();
                }
            });
        })();
    </script>

</body>
</html>