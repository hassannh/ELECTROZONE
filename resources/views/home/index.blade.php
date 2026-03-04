@extends('layouts.app')

@section('title', __('store.page_title_home') ?? 'ELECTROZONE AKKA – Premium Electronics Shop')

@section('content')

<!-- ══ HERO SECTION ════════════════════════════════════════════════════ -->
<section class="relative min-h-[500px] lg:min-h-[650px] flex items-center overflow-hidden bg-dark text-white pt-24 pb-16 lg:py-32 hero-electronic-bg">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#001a3d]/80 via-[#003080]/60 to-[#0a0a1a]/90 opacity-95"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-accent/10 rounded-full blur-[100px] translate-y-1/4 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="text-center lg:text-start" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-bold mb-6 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-accent animate-ping"></span>
                {{ __('store.hero_badge') }}
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 tracking-tighter leading-tight uppercase">
                @php
                    $titleText = (app()->getLocale() === 'ar')
                        ? ['مستقبل', 'الإلكترونيات', 'هنا']
                        : ((app()->getLocale() === 'fr')
                            ? ["L'avenir de l", "Électronique", "est ici"]
                            : ["The Future of ", "Electronics", " is Here"]);
                @endphp

                @if(app()->getLocale() === 'ar')
                    {{ $titleText[0] }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-blue-400 to-accent">{{ $titleText[1] }}</span> {{ $titleText[2] }}
                @else
                    {{ $titleText[0] }}<span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-blue-400 to-accent">{{ $titleText[1] }}</span>{{ $titleText[2] }}
                @endif
            </h1>
            <p class="text-lg md:text-xl text-white/70 mb-10 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                {{ __('store.hero_subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-full font-black text-lg hover:bg-primary-dark transition-all shadow-xl hover:shadow-primary/40 active:scale-95 flex items-center justify-center gap-2">
                    {{ __('store.shop_now') }} <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                </a>
                <a href="{{ route('products.category', 'smartphones') }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all active:scale-95">
                    {{ __('store.new_arrivals') }}
                </a>
            </div>

            <div class="mt-12 flex items-center justify-center lg:justify-start gap-8 lg:gap-12 py-8 border-t border-white/10">
                <div class="flex flex-col">
                    <span class="text-2xl lg:text-3xl font-black text-white">+500</span>
                    <label class="text-[0.65rem] lg:text-[0.75rem] font-bold text-white/40 uppercase tracking-widest">{{ __('store.stat_products') ?? 'Products' }}</label>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl lg:text-3xl font-black text-white">+50</span>
                    <label class="text-[0.65rem] lg:text-[0.75rem] font-bold text-white/40 uppercase tracking-widest">{{ __('store.stat_brands') ?? 'Brands' }}</label>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl lg:text-3xl font-black text-white">{{ __('store.stat_free') ?? 'Free' }}</span>
                    <label class="text-[0.65rem] lg:text-[0.75rem] font-bold text-white/40 uppercase tracking-widest">{{ __('store.stat_delivery') ?? 'Delivery 500+ MAD' }}</label>
                </div>
            </div>
        </div>

        <!-- Hero Visual Area -->
        <div class="hidden lg:flex relative items-center justify-center">
            <div class="w-[450px] h-[450px] bg-primary/10 border-2 border-white/10 rounded-full animate-[spin_20s_linear_infinite]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                 <div class="grid grid-cols-2 gap-4">
                     <div class="w-40 h-40 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl flex items-center justify-center text-5xl animate-[bounce_3s_infinite] shadow-2xl">📱</div>
                     <div class="w-40 h-40 bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl flex items-center justify-center text-5xl animate-[bounce_4s_infinite] translate-y-8 shadow-2xl">💻</div>
                     <div class="w-40 h-40 bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl flex items-center justify-center text-5xl animate-[bounce_5s_infinite] -translate-y-4 shadow-2xl">🎧</div>
                     <div class="w-40 h-40 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl flex items-center justify-center text-5xl animate-[bounce_6s_infinite] translate-y-4 shadow-2xl">🎮</div>
                 </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ CATEGORIES GRID ════════════════════════════════════════════════ -->
<section class="py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl lg:text-4xl font-black text-dark tracking-tighter uppercase">{{ __('store.shop_by_category') }}</h2>
            <div class="h-1 flex-1 bg-border/30 mx-6 hidden sm:block"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}" class="flex flex-col items-center justify-center p-6 bg-white border border-border/50 rounded-2xl hover:shadow-premium hover:border-primary transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:scale-150 transition-transform text-4xl pointer-events-none">{{ $category->icon ?? '📦' }}</div>
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ $category->icon ?? '📦' }}</div>
                <div class="text-sm font-black text-dark uppercase tracking-tight text-center">{{ $category->name }}</div>
                <div class="text-[0.65rem] font-bold text-light mt-1">{{ $category->products_count }} {{ __('store.items') ?? 'items' }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ FEATURED PRODUCTS ══════════════════════════════════════════════ -->
@if($featured->count())
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <div class="text-primary font-black text-xs uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-primary"></span>
                    ⭐ {{ __('store.editors_choice') ?? "Editor's Choice" }}
                </div>
                <h2 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase">{{ __('store.featured_products') }}</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-border/50 rounded-full font-bold text-sm hover:border-primary hover:text-primary transition-all">
                {{ __('store.view_all') }} <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-8">
            @foreach($featured as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ══ PROMO BANNER ════════════════════════════════════════════════════ -->
<section class="py-16 bg-[#001a3d] text-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-8 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors flex flex-col gap-4 group">
                <div class="text-4xl w-14 h-14 flex items-center justify-center bg-blue-500/20 rounded-2xl group-hover:scale-110 transition-transform">🚚</div>
                <div>
                    <h3 class="font-black text-lg mb-1 tracking-tight">{{ __('store.free_delivery_title') }}</h3>
                    <p class="text-sm text-white/50">{{ __('store.free_delivery_desc') }}</p>
                </div>
            </div>
            <div class="p-8 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors flex flex-col gap-4 group">
                <div class="text-4xl w-14 h-14 flex items-center justify-center bg-teal-500/20 rounded-2xl group-hover:scale-110 transition-transform">🔒</div>
                <div>
                    <h3 class="font-black text-lg mb-1 tracking-tight">{{ __('store.secure_payment') }}</h3>
                    <p class="text-sm text-white/50">{{ __('store.secure_payment_desc') }}</p>
                </div>
            </div>
            <div class="p-8 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors flex flex-col gap-4 group">
                <div class="text-4xl w-14 h-14 flex items-center justify-center bg-orange-500/20 rounded-2xl group-hover:scale-110 transition-transform">↩️</div>
                <div>
                    <h3 class="font-black text-lg mb-1 tracking-tight">{{ __('store.returns_title') }}</h3>
                    <p class="text-sm text-white/50">{{ __('store.returns_desc') }}</p>
                </div>
            </div>
            <div class="p-8 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors flex flex-col gap-4 group">
                <div class="text-4xl w-14 h-14 flex items-center justify-center bg-purple-500/20 rounded-2xl group-hover:scale-110 transition-transform">🎧</div>
                <div>
                    <h3 class="font-black text-lg mb-1 tracking-tight">{{ __('store.support_title') }}</h3>
                    <p class="text-sm text-white/50">{{ __('store.support_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ NEW ARRIVALS ════════════════════════════════════════════════════ -->
@if($newArrivals->count())
<section class="py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <div class="text-primary font-black text-xs uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-primary"></span>
                    🆕 {{ __('store.just_in') ?? 'Just In' }}
                </div>
                <h2 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase">{{ __('store.new_products') }}</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'created_at_desc']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-border/50 rounded-full font-bold text-sm hover:border-primary hover:text-primary transition-all">
                {{ __('store.view_all') }} <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-8">
            @foreach($newArrivals as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ══ SALE PRODUCTS ═══════════════════════════════════════════════════ -->
@if($onSale->count())
<section class="py-16 bg-linear-to-b from-white to-orange-50/30">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <div class="text-danger font-black text-xs uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-danger"></span>
                    🔥 {{ __('store.limited_time') ?? 'Limited Time' }}
                </div>
                <h2 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase">{{ __('store.sale_products') }}</h2>
            </div>
            <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-border/50 rounded-full font-bold text-sm hover:border-danger hover:text-danger transition-all">
                {{ __('store.view_all') }} <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-8">
            @foreach($onSale as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
