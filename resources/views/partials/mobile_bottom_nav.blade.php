@php
    if (request()->routeIs('admin*')) {
        $items = [
            ['route' => 'admin', 'active' => 'admin', 'label' => 'Beranda', 'icon' => '🏠'],
            ['route' => 'admin.pesanan', 'active' => 'admin.pesanan*', 'label' => 'Pesanan', 'icon' => '🛍', 'badge' => $adminUnreadOrders ?? 0, 'badge_label' => 'pesanan belum diperiksa'],
            ['route' => 'admin.custom-orders.index', 'active' => 'admin.custom-orders.*', 'label' => 'Custom', 'icon' => '🎂', 'badge' => $adminUnreadCustomOrders ?? 0, 'badge_label' => 'permintaan custom belum diperiksa'],
            ['route' => 'admin.produk', 'active' => 'admin.produk*', 'label' => 'Produk', 'icon' => '🎂'],
            ['route' => 'admin.pembayaran', 'active' => 'admin.pembayaran*', 'label' => 'Bayar', 'icon' => '💳', 'badge' => $adminUnreadPayments ?? 0, 'badge_label' => 'bukti pembayaran belum diperiksa'],
        ];
    } elseif (request()->routeIs('owner*')) {
        $items = [
            ['route' => 'owner', 'active' => 'owner', 'label' => 'Beranda', 'icon' => '🏠'],
            ['route' => 'owner.pesanan', 'active' => 'owner.pesanan', 'label' => 'Pesanan', 'icon' => '🛍'],
            ['route' => 'owner.produk', 'active' => 'owner.produk', 'label' => 'Produk', 'icon' => '🎂'],
            ['route' => 'owner.laporan', 'active' => 'owner.laporan*', 'label' => 'Laporan', 'icon' => '📊'],
            ['route' => 'owner.admin', 'active' => 'owner.admin*', 'label' => 'Admin', 'icon' => '👥'],
        ];
    } else {
        $items = [
            ['route' => 'pelanggan', 'active' => 'pelanggan', 'label' => 'Beranda', 'icon' => '⌂'],
            ['route' => 'katalog', 'active' => ['katalog', 'produk.detail'], 'label' => 'Katalog', 'icon' => '▦'],
            ['route' => 'pesanan.pelanggan', 'active' => ['pesanan.pelanggan', 'pesanan.*', 'keranjang.*', 'checkout.*', 'pembayaran.*', 'preorder.*', 'struk.pelanggan'], 'label' => 'Pesanan', 'icon' => '♧', 'badge' => $customerUnreadCount ?? 0, 'badge_label' => 'notifikasi pesanan'],
            ['route' => 'profil', 'active' => 'profil*', 'label' => 'Profil', 'icon' => '♙'],
        ];
    }
@endphp

<style>
    .notification-badge {
        display: inline-flex;
        min-width: 19px;
        height: 19px;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        padding: 0 5px;
        border-radius: 999px;
        background: #d94369;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
    }

    .unread-dot {
        display: inline-block;
        width: 9px;
        height: 9px;
        margin-left: 6px;
        border-radius: 50%;
        background: #d94369;
        box-shadow: 0 0 0 3px rgba(217, 67, 105, .13);
        vertical-align: middle;
    }

    .mobile-bottom-nav {
        display: none;
    }

    @media (max-width: 768px) {
        .sidebar,
        .dots-wrap {
            display: none !important;
        }

        body {
            padding-bottom: calc(76px + env(safe-area-inset-bottom));
        }

        .mobile-bottom-nav {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            align-items: stretch;
            justify-content: space-around;
            padding: 7px 4px calc(7px + env(safe-area-inset-bottom));
            background: #fff;
            border-top: 1px solid #f0dfe5;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
        }

        .mobile-bottom-nav a,
        .mobile-bottom-nav button {
            position: relative;
            display: flex;
            min-width: 0;
            flex: 1;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 3px 1px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #888;
            font: inherit;
            text-decoration: none;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-bottom-nav a.active,
        .mobile-bottom-nav button:hover,
        .mobile-bottom-nav a:hover {
            background: #fff0f5;
            color: #c9577c;
        }

        .mobile-bottom-nav .nav-icon {
            font-size: 20px;
            line-height: 1;
        }

        .mobile-bottom-nav .nav-label {
            overflow: hidden;
            max-width: 100%;
            font-size: 9px;
            line-height: 1.2;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mobile-bottom-nav .notification-badge {
            position: absolute;
            top: 0;
            right: 12%;
            min-width: 17px;
            height: 17px;
            margin: 0;
            padding: 0 4px;
            font-size: 10px;
        }

        .mobile-bottom-nav form {
            display: flex;
            min-width: 0;
            flex: 1;
            margin: 0;
        }
    }
</style>

<nav class="mobile-bottom-nav" aria-label="Navigasi utama">
    @foreach ($items as $item)
        @php
            $isActive = is_array($item['active'])
                ? request()->routeIs(...$item['active'])
                : request()->routeIs($item['active']);
        @endphp
        <a href="{{ route($item['route']) }}"
           class="{{ $isActive ? 'active' : '' }}"
           aria-label="{{ $item['label'] }}"
           aria-current="{{ $isActive ? 'page' : 'false' }}"
           title="{{ $item['label'] }}">
            <span class="nav-icon" aria-hidden="true">{{ $item['icon'] }}</span>
            <span class="nav-label">{{ $item['label'] }}</span>
            @if (!empty($item['badge']))
                <span class="notification-badge"
                      aria-label="{{ $item['badge'] }} {{ $item['badge_label'] ?? 'notifikasi' }}">
                    {{ $item['badge'] }}
                </span>
            @endif
        </a>
    @endforeach

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" aria-label="Logout" title="Logout">
            <span class="nav-icon" aria-hidden="true">{{ request()->routeIs('admin*', 'owner*') ? '🚪' : '↪️' }}</span>
            <span class="nav-label">Logout</span>
        </button>
    </form>
</nav>
