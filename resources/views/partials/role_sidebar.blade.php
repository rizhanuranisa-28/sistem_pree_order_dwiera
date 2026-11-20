@php
    $role = auth()->user()->role ?? 'pelanggan';

    if ($role === 'admin') {
        $roleLabel = 'ADMIN PANEL';
        $navItems = [
            ['route' => 'admin', 'active' => 'admin', 'label' => 'Beranda', 'icon' => '🏠'],
            ['route' => 'admin.pesanan', 'active' => 'admin.pesanan*', 'label' => 'Pesanan', 'icon' => '🛍️', 'badge' => $adminUnreadOrders ?? 0, 'badge_label' => 'pesanan belum diperiksa'],
            ['route' => 'admin.custom-orders.index', 'active' => 'admin.custom-orders.*', 'label' => 'Permintaan Custom', 'icon' => '🎂', 'badge' => $adminUnreadCustomOrders ?? 0, 'badge_label' => 'permintaan custom belum diperiksa'],
            ['route' => 'admin.produk', 'active' => 'admin.produk*', 'label' => 'Produk', 'icon' => '🎂'],
            ['route' => 'admin.pembayaran', 'active' => 'admin.pembayaran*', 'label' => 'Pembayaran', 'icon' => '💳', 'badge' => $adminUnreadPayments ?? 0, 'badge_label' => 'bukti pembayaran belum diperiksa'],
        ];
    } elseif ($role === 'owner') {
        $roleLabel = 'OWNER PANEL';
        $navItems = [
            ['route' => 'owner', 'active' => 'owner', 'label' => 'Beranda', 'icon' => '🏠'],
            ['route' => 'owner.pesanan', 'active' => 'owner.pesanan', 'label' => 'Pesanan', 'icon' => '🛍️'],
            ['route' => 'owner.produk', 'active' => 'owner.produk', 'label' => 'Produk', 'icon' => '🎂'],
            ['route' => 'owner.grafik', 'active' => 'owner.grafik*', 'label' => 'Grafik', 'icon' => '📊'],
            ['route' => 'owner.laporan', 'active' => 'owner.laporan*', 'label' => 'Laporan', 'icon' => '📈'],
            ['route' => 'owner.admin', 'active' => 'owner.admin*', 'label' => 'Admin', 'icon' => '👥'],
        ];
    } else {
        $roleLabel = 'PELANGGAN';
        $navItems = [
            ['route' => 'pelanggan', 'active' => 'pelanggan', 'label' => 'Beranda', 'icon' => '🏠'],
            ['route' => 'katalog', 'active' => ['katalog', 'produk.detail'], 'label' => 'Katalog', 'icon' => '🧁'],
            ['route' => 'pesanan.pelanggan', 'active' => ['pesanan.pelanggan', 'pesanan.*', 'keranjang.*', 'checkout.*', 'pembayaran.*', 'preorder.*', 'struk.pelanggan', 'custom-orders.*'], 'label' => 'Pesanan Saya', 'icon' => '🛍️', 'badge' => $customerUnreadCount ?? 0, 'badge_label' => 'notifikasi pesanan'],
            ['route' => 'profil', 'active' => 'profil*', 'label' => 'Profil', 'icon' => '👤'],
        ];
    }
@endphp

<style>
    body {
        padding-left: 238px;
    }

    .role-sidebar {
        position: fixed;
        z-index: 120;
        top: 0;
        bottom: 0;
        left: 0;
        display: flex;
        width: 238px;
        flex-direction: column;
        padding: 24px 16px;
        border-right: 1px solid #f0dfe5;
        background: #fff;
        box-shadow: 3px 0 16px rgba(80, 52, 63, .04);
    }

    .role-sidebar .role-brand {
        margin: 4px 8px 3px;
        color: #bd4775;
        font-family: Georgia, serif;
        font-size: 24px;
        font-style: italic;
        font-weight: 700;
        text-align: center;
    }

    .role-sidebar .role-label {
        margin: 0 0 26px;
        color: #a17d8c;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-align: center;
    }

    .role-sidebar .role-menu {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .role-sidebar .role-menu a,
    .role-sidebar .role-logout button {
        display: flex;
        min-height: 44px;
        align-items: center;
        gap: 11px;
        padding: 11px 12px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #756b70;
        font: inherit;
        font-size: 14px;
        text-align: left;
        text-decoration: none;
        cursor: pointer;
    }

    .role-sidebar .role-menu a:hover,
    .role-sidebar .role-menu a.active,
    .role-sidebar .role-logout button:hover {
        background: #fff0f5;
        color: #bd4775;
    }

    .role-sidebar .role-menu-icon {
        width: 22px;
        flex: 0 0 22px;
        font-size: 17px;
        text-align: center;
    }

    .role-sidebar .role-menu-label {
        min-width: 0;
    }

    .role-sidebar .notification-badge {
        margin-left: auto;
    }

    .role-sidebar .role-logout {
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid #f4e8ec;
    }

    @media (max-width: 768px) {
        body {
            padding-left: 0 !important;
        }

        .role-sidebar {
            display: none !important;
        }
    }
</style>

<aside class="sidebar role-sidebar" aria-label="Navigasi {{ strtolower($roleLabel) }}">
    <div class="role-brand">Dwiera Cake</div>
    <div class="role-label">{{ $roleLabel }}</div>

    <nav class="role-menu">
        @foreach ($navItems as $item)
            @php
                $isActive = is_array($item['active'])
                    ? request()->routeIs(...$item['active'])
                    : request()->routeIs($item['active']);
            @endphp
            <a href="{{ route($item['route']) }}"
               class="{{ $isActive ? 'active' : '' }}"
               aria-current="{{ $isActive ? 'page' : 'false' }}">
                <span class="role-menu-icon" aria-hidden="true">{{ $item['icon'] }}</span>
                <span class="role-menu-label">{{ $item['label'] }}</span>
                @if (!empty($item['badge']))
                    <span class="notification-badge"
                          aria-label="{{ $item['badge'] }} {{ $item['badge_label'] ?? 'notifikasi' }}">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="role-logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" aria-label="Logout">
                <span class="role-menu-icon" aria-hidden="true">{{ in_array($role, ['admin', 'owner'], true) ? '🚪' : '↪️' }}</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
