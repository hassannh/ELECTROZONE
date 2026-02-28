@extends('layouts.app')

@section('title', __('store.page_title_home') ?? 'ELECTROZONE AKKA – Premium Electronics Shop')

@section('content')

<!-- ══ HERO SECTION ════════════════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-content">
        <div class="hero-text">
            <div class="hero-badge">{{ __('store.hero_badge') }}</div>
            <h1>
                @if(app()->getLocale() === 'ar')
                    مستقبل <span class="gradient-text">الإلكترونيات</span> هنا
                @elseif(app()->getLocale() === 'fr')
                    L'avenir de l'<span class="gradient-text">Électronique</span> est ici
                @else
                    The Future of <span class="gradient-text">Electronics</span> is Here
                @endif
            </h1>
            <p>{{ __('store.hero_subtitle') }}</p>
            <div class="hero-btns">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">{{ __('store.shop_now') }} →</a>
                <a href="{{ route('products.category', 'smartphones') }}" class="btn btn-outline btn-lg">{{ __('store.new_arrivals') }}</a>
            </div>
            <div class="hero-stats">
                <div class="stat"><span>+500</span><label>{{ __('store.stat_products') ?? 'Products' }}</label></div>
                <div class="stat"><span>+50</span><label>{{ __('store.stat_brands') ?? 'Brands' }}</label></div>
                <div class="stat"><span>{{ __('store.stat_free') ?? 'Free' }}</span><label>{{ __('store.stat_delivery') ?? 'Delivery 500+ MAD' }}</label></div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-glow"></div>
            <div class="floating-cards">
                <div class="float-card" style="--delay:0s">📱 Galaxy S24</div>
                <div class="float-card" style="--delay:.3s">💻 MacBook Pro</div>
                <div class="float-card" style="--delay:.6s">🎧 Sony XM5</div>
                <div class="float-card" style="--delay:.9s">🎮 PS5</div>
            </div>
        </div>
    </div>
</section>

<!-- ══ CATEGORIES GRID ════════════════════════════════════════════════ -->
<section class="section categories-section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __('store.shop_by_category') }}</h2>
        </div>
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}" class="category-card">
                <div class="cat-icon">{{ $category->icon ?? '📦' }}</div>
                <div class="cat-name">{{ $category->name }}</div>
                <div class="cat-count">{{ $category->products_count }} {{ __('store.items') ?? 'items' }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ FEATURED PRODUCTS ══════════════════════════════════════════════ -->
@if($featured->count())
<section class="section products-section">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label">⭐ {{ __('store.editors_choice') ?? "Editor's Choice" }}</div>
                <h2>{{ __('store.featured_products') }}</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-sm">{{ __('store.view_all') }} →</a>
        </div>
        <div class="products-grid">
            @foreach($featured as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ══ PROMO BANNER ════════════════════════════════════════════════════ -->
<section class="promo-banner">
    <div class="container">
        <div class="promo-grid">
            <div class="promo-card promo-blue">
                <div class="promo-icon">🚚</div>
                <h3>{{ __('store.free_delivery_title') }}</h3>
                <p>{{ __('store.free_delivery_desc') }}</p>
            </div>
            <div class="promo-card promo-green">
                <div class="promo-icon">🔒</div>
                <h3>{{ __('store.secure_payment') }}</h3>
                <p>{{ __('store.secure_payment_desc') }}</p>
            </div>
            <div class="promo-card promo-orange">
                <div class="promo-icon">↩️</div>
                <h3>{{ __('store.returns_title') }}</h3>
                <p>{{ __('store.returns_desc') }}</p>
            </div>
            <div class="promo-card promo-purple">
                <div class="promo-icon">🎧</div>
                <h3>{{ __('store.support_title') }}</h3>
                <p>{{ __('store.support_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ══ NEW ARRIVALS ════════════════════════════════════════════════════ -->
@if($newArrivals->count())
<section class="section products-section">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label">🆕 {{ __('store.just_in') ?? 'Just In' }}</div>
                <h2>{{ __('store.new_products') }}</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'created_at_desc']) }}" class="btn btn-outline-sm">{{ __('store.view_all') }} →</a>
        </div>
        <div class="products-grid">
            @foreach($newArrivals as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ══ SALE PRODUCTS ═══════════════════════════════════════════════════ -->
@if($onSale->count())
<section class="section products-section sale-section">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label sale-label">🔥 {{ __('store.limited_time') ?? 'Limited Time' }}</div>
                <h2>{{ __('store.sale_products') }}</h2>
            </div>
            <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="btn btn-outline-sm">{{ __('store.view_all') }} →</a>
        </div>
        <div class="products-grid">
            @foreach($onSale as $product)
                @include('components.product-card', ['product' => $product, 'showSaleBadge' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
