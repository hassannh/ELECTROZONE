<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('is_rtl', true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('store.footer_tagline') }}">
    <title>@yield('title', 'ELECTROZONE AKKA – متجر الإلكترونيات')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/electrozone.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="autocomplete-url" content="{{ route('products.autocomplete') }}">
    @stack('styles')
</head>
<body class="{{ session('is_rtl', true) ? 'rtl' : 'ltr' }}">

<!-- ══ PREMIUM NAVBAR ════════════════════════════════════════════════════ -->
<header class="site-header" id="siteHeader">

    <!-- Top Bar -->
    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-left">
                <span id="topbarLocation">📍 {{ __('store.detecting_location') }}</span>
                <span class="topbar-sep">|</span>
                <span id="topbarDateTime">{{ __('store.topbar_date') }}</span>
            </div>
            <div class="topbar-right">
                <!-- Language Switcher -->
                <div class="lang-switcher" id="langSwitcher">
                    <button class="lang-btn" id="langToggle" aria-expanded="false">
                        @php $locale = app()->getLocale(); @endphp
                        <span class="lang-flag">
                            @if($locale === 'ar') 🇲🇦 عربي
                            @elseif($locale === 'fr') 🇫🇷 Français
                            @else 🇬🇧 English @endif
                        </span>
                        <svg class="chevron" width="12" height="12" viewBox="0 0 12 12"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="lang-dropdown" id="langDropdown">
                        <a href="{{ route('lang.switch', 'ar') }}" class="lang-option {{ $locale === 'ar' ? 'active' : '' }}">
                            <span>🇲🇦</span> العربية
                        </a>
                        <a href="{{ route('lang.switch', 'fr') }}" class="lang-option {{ $locale === 'fr' ? 'active' : '' }}">
                            <span>🇫🇷</span> Français
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-option {{ $locale === 'en' ? 'active' : '' }}">
                            <span>🇬🇧</span> English
                        </a>
                    </div>
                </div>
                <a href="{{ route('cart.index') }}" class="topbar-cart-mini">
                    🛒 <span id="cartCountTop">{{ count(session('cart', [])) }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container navbar-inner">

            <!-- Brand -->
            <a href="{{ route('home') }}" class="navbar-brand">
                <span class="brand-lightning">⚡</span>
                <span class="brand-text">ELECTRO<span class="brand-zone">ZONE</span> <span class="brand-akka">AKKA</span></span>
            </a>

            <!-- Search Bar -->
            <div class="search-wrapper" id="searchWrapper">
                <form class="navbar-search" action="{{ route('products.search') }}" method="GET" id="searchForm" autocomplete="off">
                    <div class="search-inner">
                        <svg class="search-icon-left" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input
                            type="text"
                            name="query"
                            id="searchInput"
                            placeholder="{{ __('store.search_placeholder') }}"
                            value="{{ request('query') }}"
                            autocomplete="off"
                            spellcheck="false"
                        >
                        <button type="submit" class="search-submit-btn">
                            {{ app()->getLocale() === 'ar' ? 'بحث' : (app()->getLocale() === 'fr' ? 'Chercher' : 'Search') }}
                        </button>
                    </div>
                    <!-- Autocomplete Dropdown -->
                    <div class="search-dropdown" id="searchDropdown">
                        <div class="search-dropdown-inner" id="searchResults"></div>
                    </div>
                </form>
            </div>

            <!-- Right Actions -->
            <div class="navbar-actions">
                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="cart-btn-premium" id="cartBtn">
                    <div class="cart-icon-wrap">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span class="cart-count" id="cartBadge">{{ count(session('cart', [])) }}</span>
                    </div>
                    <span class="cart-label">{{ __('store.cart') }}</span>
                </a>

                <!-- Mobile Toggle -->
                <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
                    <span class="ham-line"></span>
                    <span class="ham-line"></span>
                    <span class="ham-line"></span>
                </button>
            </div>

        </div>
    </nav>

    <!-- Category Strip -->
    <div class="category-strip" id="categoryStrip">
        <div class="container category-strip-inner">
            @php $allCategories = \App\Models\Category::active()->parents()->get(); @endphp
            <a href="{{ route('home') }}" class="cat-pill {{ request()->routeIs('home') ? 'active' : '' }}">
                🏠 {{ app()->getLocale() === 'ar' ? 'الرئيسية' : (app()->getLocale() === 'fr' ? 'Accueil' : 'Home') }}
            </a>
            @foreach($allCategories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="cat-pill">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
            @endforeach
            <a href="{{ route('products.index') }}" class="cat-pill cat-pill-all">
                {{ __('store.all_products') }} →
            </a>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-header">
            <span class="brand-text" style="font-weight:800;font-size:1.1rem;">⚡ ELECTROZONE AKKA</span>
            <button class="drawer-close" id="drawerClose">✕</button>
        </div>
        <div class="mobile-search">
            <form action="{{ route('products.search') }}" method="GET">
                <div class="mobile-search-inner">
                    <input type="text" name="query" placeholder="{{ __('store.search_placeholder') }}">
                    <button type="submit">🔍</button>
                </div>
            </form>
        </div>
        <nav class="mobile-nav">
            @foreach($allCategories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="mobile-nav-link">
                <span class="mobile-nav-icon">{{ $cat->icon }}</span>
                <span>{{ $cat->name }}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mobile-nav-arrow" style="{{ session('is_rtl', true) ? 'transform:scaleX(-1)' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            @endforeach
            <a href="{{ route('products.index') }}" class="mobile-nav-link mobile-nav-all">
                <span class="mobile-nav-icon">🛍️</span>
                <span>{{ __('store.all_products') }}</span>
            </a>
        </nav>
        <!-- Mobile Language -->
        <div class="mobile-lang">
            <p>{{ __('store.language') }}:</p>
            <div class="mobile-lang-btns">
                <a href="{{ route('lang.switch', 'ar') }}" class="mobile-lang-opt {{ app()->getLocale() === 'ar' ? 'active' : '' }}">🇲🇦 عربي</a>
                <a href="{{ route('lang.switch', 'fr') }}" class="mobile-lang-opt {{ app()->getLocale() === 'fr' ? 'active' : '' }}">🇫🇷 FR</a>
                <a href="{{ route('lang.switch', 'en') }}" class="mobile-lang-opt {{ app()->getLocale() === 'en' ? 'active' : '' }}">🇬🇧 EN</a>
            </div>
        </div>
        <a href="{{ route('cart.index') }}" class="mobile-cart-link">
            🛒 {{ __('store.cart') }} ({{ count(session('cart', [])) }})
        </a>
    </div>
    <div class="mobile-overlay" id="mobileOverlay"></div>

</header>

<!-- ══ FLASH MESSAGES ══════════════════════════════════════════════════ -->
@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">❌ {{ session('error') }}</div>
@endif

<!-- ══ MAIN CONTENT ════════════════════════════════════════════════════ -->
<main class="main-content">
    @yield('content')
</main>

<!-- ══ FOOTER ══════════════════════════════════════════════════════════ -->
<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <div class="footer-logo">⚡ ELECTROZONE AKKA</div>
            <p>{{ __('store.footer_tagline') }}</p>
            <div class="footer-social">
                <a href="#" class="social-btn">f</a>
                <a href="#" class="social-btn">in</a>
                <a href="#" class="social-btn">tw</a>
            </div>
        </div>
        <div class="footer-col">
            <h4>{{ __('store.categories') }}</h4>
            @foreach((\App\Models\Category::active()->parents()->take(6)->get()) as $cat)
            <a href="{{ route('products.category', $cat->slug) }}">{{ $cat->name }}</a>
            @endforeach
        </div>
        <div class="footer-col">
            <h4>{{ __('store.customer_service') }}</h4>
            <a href="#">{{ __('store.about_us') }}</a>
            <a href="#">{{ __('store.contact') }}</a>
            <a href="#">{{ __('store.shipping_policy') }}</a>
            <a href="#">{{ __('store.returns') }}</a>
            <a href="#">{{ __('store.faq') }}</a>
        </div>
        <div class="footer-col">
            <h4>{{ __('store.contact') }}</h4>
            <p>{{ __('store.location') }}</p>
            <p>📞 +212 5XX-XXXXXX</p>
            <p>✉️ hello@electrozone.ma</p>
            <p style="margin-top:1rem;font-size:.85rem;font-weight:600;color:var(--accent)">{{ __('store.working_hours') }}</p>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>© {{ date('Y') }} ELECTROZONE AKKA. All rights reserved. | 🇲🇦</p>
            <div class="payment-badges">
                <span>💵 {{ __('store.cash_on_delivery') }}</span>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top -->
<button id="scrollTop" class="back-to-top" aria-label="Back to top">↑</button>

<script src="{{ asset('js/electrozone.js') }}"></script>
@stack('scripts')
</body>
</html>
