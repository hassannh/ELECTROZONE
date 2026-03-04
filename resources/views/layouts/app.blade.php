<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('is_rtl', true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('store.footer_tagline') }}">
    <title>@yield('title', 'ELECTROZONE AKKA – متجر الإلكترونيات')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = '/build/' . $manifest['resources/css/app.css']['file'];
        $jsFile  = '/build/' . $manifest['resources/js/app.js']['file'];
    @endphp
    <link rel="stylesheet" href="{{ $cssFile }}">
    <script src="{{ $jsFile }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="autocomplete-url" content="{{ route('products.autocomplete') }}">
    @stack('styles')
</head>
<body class="{{ session('is_rtl', true) ? 'rtl' : 'ltr' }}">

    <!-- This section was removed to implement inline expanding search -->

<!-- ══ PREMIUM NAVBAR ════════════════════════════════════════════════════ -->
<header class="sticky top-0 z-50 transition-all duration-300" id="siteHeader">

    <!-- Top Bar -->
    <div class="top-bar-solid text-white/75 text-[0.78rem] font-medium border-b border-white/8">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-2 gap-4">
            <div class="hidden md:flex items-center gap-2">
                <span id="topbarLocation">📍 {{ __('store.detecting_location') }}</span>
                <span class="opacity-35">|</span>
                <span id="topbarDateTime">{{ __('store.topbar_date') }}</span>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                <!-- Language Switcher -->
                <div class="relative group" id="langSwitcher">
                    <button class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 border border-white/20 rounded-md text-white/90 hover:bg-white/15 transition-colors" id="langToggle" aria-expanded="false">
                        @php $locale = app()->getLocale(); @endphp
                        <span class="flex items-center gap-1">
                            @if($locale === 'ar') 🇲🇦 عربي
                            @elseif($locale === 'fr') 🇫🇷 Français
                            @else 🇬🇧 English @endif
                        </span>
                        <svg class="transition-transform duration-200" width="12" height="12" viewBox="0 0 12 12"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="absolute top-full right-0 mt-1 w-32 bg-white rounded-lg shadow-premium border border-border py-1 z-50 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 [&.open]:opacity-100 [&.open]:visible [&.open]:translate-y-0 transition-all duration-200" id="langDropdown">
                        <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center gap-2 px-3 py-2 text-dark hover:bg-surface transition-colors {{ $locale === 'ar' ? 'bg-surface font-bold' : '' }}">
                            <span>🇲🇦</span> العربية
                        </a>
                        <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center gap-2 px-3 py-2 text-dark hover:bg-surface transition-colors {{ $locale === 'fr' ? 'bg-surface font-bold' : '' }}">
                            <span>🇫🇷</span> Français
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-3 py-2 text-dark hover:bg-surface transition-colors {{ $locale === 'en' ? 'bg-surface font-bold' : '' }}">
                            <span>🇬🇧</span> English
                        </a>
                    </div>
                </div>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white/10 border border-white/20 rounded-md text-white/90 hover:bg-white/20 transition-all font-semibold text-[0.82rem]">
                    <span class="text-base">🛒</span>
                    <span id="cartCountTop" class="min-w-[1.2rem] text-center">{{ count(session('cart', [])) }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white border-b border-black/10 shadow-sm" id="navbar">
        <div class="max-w-7xl mx-auto flex items-center gap-5 px-6 py-3">

            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <img src="{{ asset('images/logo.jpeg') }}" alt="ELECTROZONE Logo" class="h-14 w-auto object-contain transition-transform group-hover:scale-105">
                <span class="text-[1.1rem] font-black text-dark tracking-tighter uppercase hidden sm:block">
                    ELECTRO<span class="text-primary">ZONE</span> <span class="bg-primary text-white px-1.5 py-0.5 rounded text-[0.8em] font-black">AKKA</span>
                </span>
            </a>

            <!-- Desktop Premium Cart & Search -->
            <div class="hidden md:flex items-center flex-1 max-w-4xl gap-6 ml-6 mr-4">
                {{-- Live Search Bar (No Button) --}}
                <div class="flex-1" id="searchWrapper">
                    <form class="relative group" action="{{ route('products.search') }}" method="GET" id="searchForm" autocomplete="off">
                        <div class="relative flex items-center">
                            <svg class="absolute ltr:left-5 rtl:right-5 text-mid pointer-events-none group-focus-within:text-primary transition-colors" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input
                                type="text"
                                name="query"
                                id="searchInput"
                                placeholder="{{ __('store.search_placeholder') }}"
                                value="{{ request('query') }}"
                                autocomplete="off"
                                spellcheck="false"
                                class="w-full bg-surface/50 border-2 border-transparent py-3 ltr:pl-14 ltr:pr-6 rtl:pr-14 rtl:pl-6 rounded-2xl text-[0.95rem] font-bold focus:outline-none focus:border-primary focus:bg-white transition-all shadow-inner"
                            >
                        </div>
                        <!-- Autocomplete Dropdown -->
                        <div class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-premium border border-border overflow-hidden z-50 hidden" id="searchDropdown">
                            <div class="p-2" id="searchResults"></div>
                        </div>
                    </form>
                </div>

                {{-- Premium Cart Total (Right of Search bar) --}}
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 group shrink-0" id="desktopCartTotal">
                    <div class="relative w-12 h-12 flex items-center justify-center bg-primary rounded-2xl shadow-premium group-hover:scale-105 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span class="absolute -top-2 -right-2 bg-accent text-white text-[0.7rem] font-black min-w-[1.4rem] h-[1.4rem] flex items-center justify-center rounded-full border-2 border-primary shadow-lg" id="cartCountTop">{{ count(session('cart', [])) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[0.65rem] font-bold text-light uppercase tracking-wider">{{ __('store.cart') }}</span>
                        <span class="text-[0.95rem] font-black text-dark" id="cartTotalHeader">
                            @php 
                                $cartArr = session('cart', []);
                                $totalVal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartArr));
                            @endphp
                            {{ number_format($totalVal, 2) }} MAD
                        </span>
                    </div>
                </a>
            </div>

            <!-- Mobile Expanding Search -->
            <div class="flex-1 relative md:hidden flex items-center justify-end" id="mobileSearchContainer">
                <div class="absolute inset-0 bg-white z-40 flex items-center gap-2 opacity-0 pointer-events-none transition-all duration-300 -translate-x-full ltr:-translate-x-full rtl:translate-x-full" id="mobileSearchInputWrapper">
                    <button class="w-10 h-10 flex items-center justify-center text-dark" onclick="toggleMobileSearch()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                    <input type="text" id="mobileSearchInput" placeholder="{{ __('store.search_placeholder') }}" class="flex-1 bg-surface py-2.5 px-4 rounded-xl text-sm font-bold focus:outline-none border-2 border-transparent focus:border-primary transition-all">
                </div>
                
                {{-- Mobile Results Dropdown --}}
                <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-border overflow-hidden z-[100] hidden mx-[-1rem] w-[calc(100%+2rem)]" id="mobileSearchDropdown">
                    <div class="p-2" id="mobileSearchResults"></div>
                </div>

                <div class="flex items-center gap-3" id="mobileHeaderActions">
                    {{-- Mobile Search Trigger --}}
                    <button class="flex md:hidden items-center justify-center w-10 h-10 bg-surface rounded-full text-dark shadow-sm border border-border/50" onclick="toggleMobileSearch()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </button>

                    {{-- Standard cart button (Visible on mobile only now) --}}
                    <a href="{{ route('cart.index') }}" class="flex md:hidden items-center gap-2 bg-primary text-white px-4 py-2 rounded-full font-bold shadow-md" id="cartBtn">
                        <div class="relative">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            <span class="absolute -top-2 -right-2 bg-accent text-white text-[0.65rem] font-black min-w-[1.2rem] h-[1.2rem] flex items-center justify-center rounded-full border-2 border-primary" id="cartBadge">{{ count(session('cart', [])) }}</span>
                        </div>
                    </a>

                    <button class="flex flex-col items-center justify-center w-10 h-10 gap-1.5" id="hamburger">
                        <span class="w-6 h-0.5 bg-dark rounded-full transition-all"></span>
                        <span class="w-6 h-0.5 bg-dark rounded-full transition-all"></span>
                        <span class="w-6 h-0.5 bg-dark rounded-full transition-all"></span>
                    </button>
                </div>
            </div>

        </div>
    </nav>

    <!-- Category Strip (Desktop) -->
    <div class="bg-primary overflow-x-auto no-scrollbar border-b-2 border-white/15 hidden md:block" id="categoryStrip">
        <div class="max-w-7xl mx-auto flex items-center px-6 gap-1 overflow-x-auto no-scrollbar">
            @php $allCategories = \App\Models\Category::active()->parents()->get(); @endphp
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-white/80 text-[0.82rem] font-semibold whitespace-nowrap rounded-full transition-all hover:bg-white/20 hover:text-white my-1.5 {{ request()->routeIs('home') ? 'bg-white/20 text-white' : '' }}">
                🏠 {{ app()->getLocale() === 'ar' ? 'الرئيسية' : (app()->getLocale() === 'fr' ? 'Accueil' : 'Home') }}
            </a>
            @foreach($allCategories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-white/80 text-[0.82rem] font-semibold whitespace-nowrap rounded-full transition-all hover:bg-white/20 hover:text-white my-1.5">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
            @endforeach
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-white bg-white/15 border border-white/30 text-[0.82rem] font-semibold whitespace-nowrap rounded-full transition-all hover:bg-accent hover:border-accent ms-auto my-1.5">
                {{ __('store.all_products') }} →
            </a>
        </div>
    </div>

    {{-- Mobile Category Horizontal Scroll --}}
    <div class="bg-white overflow-x-auto no-scrollbar border-b border-border flex md:hidden" id="mobileCatScroll">
        <div class="flex items-center gap-2 px-4 py-2.5">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-9 h-9 bg-surface border border-border rounded-full text-base transition-all {{ request()->routeIs('home') ? 'bg-primary border-primary text-white' : '' }}">🏠</a>
            @foreach($allCategories as $cat)
            <a href="{{ route('products.category', $cat->slug) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-surface border border-border rounded-full text-[0.8rem] font-semibold whitespace-nowrap text-dark transition-all {{ request()->routeIs('products.category') && request()->route('slug') == $cat->slug ? 'bg-primary border-primary text-white' : '' }}">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
            @endforeach
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-surface border border-border rounded-full text-[0.8rem] font-semibold whitespace-nowrap text-dark transition-all">🛍️ All</a>
        </div>
    </div>

    <!-- Creative Mobile Drawer -->
    <div class="fixed top-0 left-0 h-full w-[320px] glass-sidebar z-[10000] shadow-2xl flex flex-col -translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] overflow-hidden ltr:left-0 ltr:-translate-x-full rtl:right-0 rtl:translate-x-full" id="mobileDrawer">
        
        <!-- Premium Header Area -->
        <div class="relative bg-gradient-to-br from-[#001a3d] to-[#003080] p-6 pt-10 pb-12 overflow-hidden shrink-0">
            <!-- Animated background blobs -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-accent/20 rounded-full blur-2xl animate-pulse delay-700"></div>
            
            <div class="relative z-10 flex items-center justify-between mb-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="h-12 w-auto object-contain">
                    <span class="text-lg font-black text-white tracking-tighter uppercase">ELECTRO<span class="text-primary">ZONE</span></span>
                </a>
                <button class="w-10 h-10 flex items-center justify-center bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all active:scale-90" id="drawerClose">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-2 gap-3 relative z-10">
                <a href="{{ route('cart.index') }}" class="flex flex-col items-center gap-2 p-3 bg-white/10 backdrop-blur-lg border border-white/10 rounded-2xl transition-all active:scale-95 group">
                    <div class="w-10 h-10 flex items-center justify-center bg-primary rounded-xl shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <span class="text-[0.65rem] font-black text-white uppercase tracking-widest">{{ __('store.cart') }}</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-2 p-3 bg-white/10 backdrop-blur-lg border border-white/10 rounded-2xl transition-all active:scale-95 group">
                    <div class="w-10 h-10 flex items-center justify-center bg-accent rounded-xl shadow-lg shadow-accent/30 group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <span class="text-[0.65rem] font-black text-white uppercase tracking-widest">Store</span>
                </a>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto no-scrollbar p-6 space-y-8">
            
            <!-- Search Experience -->
            <div class="relative group">
                <form action="{{ route('products.search') }}" method="GET" class="relative">
                    <input type="text" name="query" placeholder="{{ __('store.search_placeholder') }}" 
                           class="w-full pl-12 pr-6 py-4 bg-surface border-2 border-transparent rounded-2xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all shadow-inner">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-light group-focus-within:text-primary transition-colors">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                </form>
            </div>

            <!-- Categories -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-[0.65rem] font-black text-light uppercase tracking-[0.2em]">{{ __('store.categories') }}</h4>
                    <a href="{{ route('products.index') }}" class="text-[0.6rem] font-black text-primary uppercase tracking-widest hover:underline">View All →</a>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    @foreach($allCategories as $cat)
                    <a href="{{ route('products.category', $cat->slug) }}" class="group flex items-center gap-4 p-4 bg-white border border-border/40 rounded-2xl transition-all hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 active:scale-[0.98]">
                        <div class="w-12 h-12 flex items-center justify-center bg-surface group-hover:bg-primary/10 rounded-xl text-2xl transition-colors">
                            {{ $cat->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block font-bold text-dark truncate">{{ $cat->name }}</span>
                            <span class="text-[0.65rem] font-bold text-light uppercase tracking-tight">Explore Collection</span>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-border group-hover:text-primary group-hover:translate-x-1 transition-all rtl:rotate-180"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Promo Banner Card -->
            <div class="bg-gradient-to-br from-accent to-accent-dark rounded-3xl p-6 relative overflow-hidden float-slow">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10 flex flex-col gap-3 text-white">
                    <span class="bg-white/20 px-2 py-0.5 rounded-lg text-[0.6rem] font-black uppercase inline-block self-start tracking-widest">New Arrival</span>
                    <h5 class="text-xl font-black leading-tight">Up to 40% Off New Items</h5>
                    <a href="{{ route('products.index') }}" class="px-5 py-2 bg-white text-accent rounded-xl text-xs font-black uppercase tracking-widest w-fit shadow-lg active:scale-95 transition-transform">Shop Deal</a>
                </div>
            </div>

        </div>


        <!-- Sticky Bottom Area -->
        <div class="p-6 bg-surface border-t border-border/30 gap-6 flex flex-col shrink-0">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-light uppercase tracking-widest">{{ __('store.language') }}</span>
                <div class="flex gap-2">
                    @foreach(['ar' => '🇲🇦', 'fr' => '🇫🇷', 'en' => '🇬🇧'] as $l => $f)
                        <a href="{{ route('lang.switch', $l) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-border/50 text-sm transition-all hover:bg-white {{ app()->getLocale() === $l ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-surface text-dark' }}">
                            {{ $f }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="fixed inset-0 bg-black/40 z-[9999] backdrop-blur-[4px] hidden transition-all duration-500 opacity-0" id="mobileOverlay"></div>


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

<!-- ══ FOOTER ════════════════════════════════════════════════════════════ -->
<footer class="bg-[#0a0a1a] text-white/70 pt-16 mt-16">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] gap-8 pb-12">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <img src="{{ asset('images/logo.jpeg') }}" alt="ELECTROZONE Logo" class="h-16 w-auto object-contain brightness-0 invert opacity-90 hover:opacity-100 transition-opacity">
            </a>
            <p class="text-sm leading-relaxed mb-5">{{ __('store.footer_tagline') }}</p>
            <div class="flex gap-2">
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-primary hover:text-white transition-all font-bold">f</a>
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-primary hover:text-white transition-all font-bold">t</a>
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-primary hover:text-white transition-all font-bold">i</a>
            </div>
        </div>

        <div>
            <h4 class="text-white text-[0.9rem] font-bold mb-4 uppercase tracking-[0.05em]">{{ __('store.shop') }}</h4>
            <div class="flex flex-col gap-2">
                <a href="{{ route('products.index') }}" class="text-sm text-white/60 hover:text-primary transition-colors">{{ __('store.all_products') }}</a>
                @foreach($allCategories as $cat)
                <a href="{{ route('products.category', $cat->slug) }}" class="text-sm text-white/60 hover:text-primary transition-colors">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>

        <div>
            <h4 class="text-white text-[0.9rem] font-bold mb-4 uppercase tracking-[0.05em]">{{ __('store.customer_service') }}</h4>
            <div class="flex flex-col gap-2">
                <a href="#" class="text-sm text-white/60 hover:text-primary transition-colors">{{ __('store.contact_us') }}</a>
                <a href="#" class="text-sm text-white/60 hover:text-primary transition-colors">{{ __('store.shipping_policy') }}</a>
                <a href="#" class="text-sm text-white/60 hover:text-primary transition-colors">{{ __('store.returns_refunds') }}</a>
            </div>
        </div>

        <div>
            <h4 class="text-white text-[0.9rem] font-bold mb-4 uppercase tracking-[0.05em]">{{ __('store.contact_info') }}</h4>
            <div class="flex flex-col gap-2">
                <p class="text-sm text-white/60 mb-2">{{ __('store.location') }}</p>
                <p class="text-sm text-white/60 mb-2">📞 {{ __('store.phone_number') }}</p>
                <p class="text-sm text-white/60 mb-2">✉️ contact@electrozone.ma</p>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10 py-5">
        <div class="max-w-7xl mx-auto px-6 flex flex-wrap gap-4 items-center justify-between">
            <p class="text-xs text-white/40">© {{ date('Y') }} ELECTROZONE AKKA. {{ __('store.all_rights_reserved') }}.</p>
            <div class="flex items-center gap-1.5 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all cursor-default">
                <span class="bg-white/10 px-2 py-0.5 rounded text-[0.65rem] font-bold">VISA</span>
                <span class="bg-white/10 px-2 py-0.5 rounded text-[0.65rem] font-bold">MASTERCARD</span>
                <span class="bg-white/10 px-2 py-0.5 rounded text-[0.65rem] font-bold">CASH ON DELIVERY</span>
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
