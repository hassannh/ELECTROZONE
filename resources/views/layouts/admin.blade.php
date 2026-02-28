<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') – ELECTROZONE AKKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/electrozone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Sidebar Toggle (Mobile Only) -->
    <button class="admin-mobile-toggle" id="adminMobileToggle" aria-label="Toggle Menu">☰</button>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <span>⚡</span>
            <span class="sidebar-brand-text">EZ Admin</span>
            <button class="sidebar-close-mobile" id="adminSidebarClose">×</button>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Orders
                @php $newOrderCount = \App\Models\Order::where('order_status','new')->count(); @endphp
                @if($newOrderCount > 0)
                    <span class="sidebar-badge">{{ $newOrderCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="nav-icon">🛍️</span> Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">📂</span> Categories
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-user">
                <span class="admin-avatar">{{ substr(auth('admin')->user()->name, 0, 1) }}</span>
                <span>{{ auth('admin')->user()->name }}</span>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="sidebar-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')">☰</button>
            <h2>@yield('page-title', 'Dashboard')</h2>
            <a href="{{ route('home') }}" class="btn-view-store" target="_blank">🌐 View Store</a>
        </header>

        @if(session('success'))
        <div class="admin-alert admin-alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="admin-alert admin-alert-error">❌ {{ session('error') }}</div>
        @endif

        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('js/electrozone.js') }}"></script>
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('adminMobileToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminSidebarOverlay');
        const close = document.getElementById('adminSidebarClose');

        function openSidebar() {
            sidebar?.classList.add('open');
            overlay?.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar?.classList.remove('open');
            overlay?.classList.remove('open');
            document.body.style.overflow = '';
        }

        toggle?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);
        close?.addEventListener('click', closeSidebar);
    });
</script>
</body>
</html>
