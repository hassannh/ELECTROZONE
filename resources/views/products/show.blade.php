@extends('layouts.app')

@section('title', $product->name . ' – ELECTROZONE AKKA')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6 lg:py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs lg:text-sm font-bold text-light uppercase tracking-widest mb-8 lg:mb-12 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-border">/</span>
        @if($product->category)
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
        <span class="text-border">/</span>
        @endif
        <span class="text-dark truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
        {{-- Gallery --}}
        <div class="flex flex-col-reverse lg:flex-row gap-4">
            @php $images = $product->images; @endphp
            @if($images->count() > 1)
            <div class="flex flex-row lg:flex-col gap-3 overflow-x-auto lg:overflow-visible no-scrollbar shrink-0">
                @foreach($images as $img)
                <button class="w-16 h-16 lg:w-20 lg:h-20 shrink-0 border-2 rounded-xl overflow-hidden transition-all {{ $loop->first ? 'border-primary shadow-md' : 'border-transparent opacity-60 hover:opacity-100 hover:border-border' }}"
                        onclick="switchImage(this, '{{ asset('storage/' . $img->path) }}')">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif

            <div class="relative flex-1 bg-surface rounded-3xl overflow-hidden border border-border/50 group shadow-sm">
                @if($images->count())
                    <img id="mainImg"
                         src="{{ asset('storage/' . $images->first()->path) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-contain aspect-square cursor-zoom-in group-hover:scale-105 transition-transform duration-700"
                         onclick="openLightbox(this.src)">
                    <div class="absolute bottom-4 right-4 bg-dark/20 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-[0.65rem] font-black pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">🔍 CLICK TO ZOOM</div>
                @else
                    <div class="w-full aspect-square flex items-center justify-center text-7xl bg-surface animate-pulse">📦</div>
                @endif
            </div>
        </div>

        {{-- Product Info --}}
        <div class="flex flex-col h-full items-start">
            <div class="text-[0.7rem] lg:text-[0.8rem] font-black text-primary uppercase tracking-[0.2em] mb-3 bg-primary/5 px-3 py-1 rounded-full">{{ $product->brand }}</div>
            <h1 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter leading-tight mb-4 uppercase">{{ $product->name }}</h1>

            <div class="flex flex-wrap gap-2 mb-8">
                @if($product->is_new)<span class="bg-primary text-white text-[0.7rem] font-black px-2.5 py-1 rounded shadow-sm">NEW</span>@endif
                @if($product->is_on_sale)<span class="bg-danger text-white text-[0.7rem] font-black px-2.5 py-1 rounded shadow-sm uppercase tracking-wider">SALE</span>@endif
                @if($product->is_featured)<span class="bg-yellow-400 text-dark text-[0.7rem] font-black px-2.5 py-1 rounded shadow-sm">⭐ FEATURED</span>@endif
            </div>

            <div class="flex items-baseline gap-4 mb-8">
                <span class="text-3xl lg:text-5xl font-black text-primary tracking-tight" id="pdpPriceDisplay">{{ number_format($product->price, 2) }} <span class="text-[0.5em]">MAD</span></span>
                @if($product->old_price)
                <span class="text-lg lg:text-2xl text-light line-through decoration-danger/40">{{ number_format($product->old_price, 2) }}</span>
                @php $discount = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                <span class="bg-danger/10 text-danger text-xs font-black px-2 py-1 rounded">SAVE {{ $discount }}%</span>
                @endif
            </div>

            <p class="text-base lg:text-lg text-mid font-medium mb-10 leading-relaxed max-w-xl">{{ $product->short_description }}</p>

            <div class="mb-10 flex items-center gap-3">
                @if($product->isInStock())
                    <span class="flex items-center gap-2 px-3 py-1.5 bg-accent/10 text-accent rounded-full text-sm font-bold">
                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span> IN STOCK
                    </span>
                    @if($product->stock_quantity <= 10)
                        <span class="text-xs font-black text-orange-500 uppercase tracking-widest">— Only {{ $product->stock_quantity }} left!</span>
                    @endif
                @else
                    <span class="px-3 py-1.5 bg-danger/10 text-danger rounded-full text-sm font-bold uppercase tracking-widest">OUT OF STOCK</span>
                @endif
            </div>

            @if($product->isInStock())
            <form action="{{ route('cart.add') }}" method="POST" class="w-full max-w-md bg-surface p-6 rounded-3xl border border-border/50 shadow-sm mb-10" id="pdpAddForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div class="flex items-center bg-white border border-border rounded-xl p-1 shrink-0">
                        <button type="button" onclick="changeQty(-1)" class="w-10 h-10 flex items-center justify-center text-xl font-bold hover:bg-surface rounded-lg transition-colors">−</button>
                        <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-14 text-center text-lg font-black bg-transparent outline-none">
                        <button type="button" onclick="changeQty(1)" class="w-10 h-10 flex items-center justify-center text-xl font-bold hover:bg-surface rounded-lg transition-colors">+</button>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white rounded-xl font-black text-lg hover:bg-primary-dark transition-all shadow-xl hover:shadow-primary/40 active:scale-95 flex items-center justify-center gap-3">
                        🛒 Add to Cart
                    </button>
                </div>
            </form>

            {{-- Trust Badges --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full border-t border-border/50 pt-10">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">🚚</span>
                    <div>
                        <strong class="block text-xs font-black uppercase tracking-widest text-dark">Free Delivery</strong>
                        <small class="text-[0.65rem] font-bold text-light uppercase">Orders over 500 MAD</small>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-2xl">🔒</span>
                    <div>
                        <strong class="block text-xs font-black uppercase tracking-widest text-dark">Secure Payment</strong>
                        <small class="text-[0.65rem] font-bold text-light uppercase">Cash on delivery</small>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-2xl">↩️</span>
                    <div>
                        <strong class="block text-xs font-black uppercase tracking-widest text-dark">Easy Returns</strong>
                        <small class="text-[0.65rem] font-bold text-light uppercase">7-day return policy</small>
                    </div>
                </div>
            </div>

            @else
            <button class="w-full max-w-md py-4 bg-surface text-light rounded-xl font-black text-lg cursor-not-allowed border border-border mb-10 uppercase tracking-widest" disabled>OUT OF STOCK</button>
            @endif
        </div>
    </div>

    {{-- Tabs: Description + Specs --}}
    <div class="mt-16 lg:mt-24">
        <div class="flex border-b border-border mb-10 overflow-x-auto no-scrollbar">
            <button class="px-8 py-5 text-sm lg:text-base font-black uppercase tracking-widest transition-all relative after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1 after:bg-primary active-tab-btn" onclick="switchTab(event, 'desc')">Description</button>
            @if($product->specifications)
            <button class="px-8 py-5 text-sm lg:text-base font-black text-light uppercase tracking-widest hover:text-dark transition-all relative after:absolute after:bottom-0 after:left-0 after:right-0 after:h-1 after:bg-transparent" onclick="switchTab(event, 'specs')">Specifications</button>
            @endif
        </div>

        <div id="tab-desc" class="tab-content block">
            <div class="prose prose-lg max-w-none text-mid font-medium leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        @if($product->specifications)
        <div id="tab-specs" class="tab-content hidden">
            <div class="bg-surface rounded-3xl overflow-hidden border border-border/50">
                <table class="w-full text-left">
                    @foreach($product->specifications as $key => $value)
                    <tr class="border-b border-border/50 last:border-0 hover:bg-white transition-colors">
                        <th class="px-6 py-4 text-xs font-black text-light uppercase tracking-widest w-1/3">{{ $key }}</th>
                        <td class="px-6 py-4 text-sm font-bold text-dark">{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Related Products --}}
    @if($related->count())
    <div class="mt-20 lg:mt-32">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl lg:text-4xl font-black text-dark tracking-tighter uppercase">You Might Also Like</h2>
            <div class="h-1 flex-1 bg-border/20 mx-8 hidden sm:block"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-8">
            @foreach($related as $rel)
                @include('components.product-card', ['product' => $rel])
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Sticky Add-to-Cart Bar --}}
@if($product->isInStock())
<div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl border-t border-border z-[9997] p-4 translate-y-full transition-transform duration-300 shadow-[0_-8px_32px_rgba(0,0,0,0.1)]" id="stickyCartBar">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div class="hidden sm:flex items-center gap-3">
            @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->path) }}" alt="{{ $product->name }}" class="w-12 h-12 object-contain bg-surface rounded-lg">
            @endif
            <div class="flex flex-col">
                <span class="text-sm font-black text-dark truncate max-w-[300px]">{{ $product->name }}</span>
                <span class="text-xs font-bold text-primary">{{ number_format($product->price, 2) }} MAD</span>
            </div>
        </div>
        <button class="flex-1 sm:flex-none px-10 py-3.5 bg-primary text-white rounded-xl font-black text-sm hover:bg-primary-dark transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95" onclick="document.getElementById('pdpAddForm').requestSubmit()">
            🛒 <span class="hidden xs:inline">Add to Cart</span>
        </button>
    </div>
</div>
@endif

{{-- Image Lightbox --}}
<div class="fixed inset-0 bg-dark/95 z-[10005] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-6" id="lightboxOverlay" onclick="closeLightbox()">
    <button class="absolute top-6 right-6 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full text-white text-2xl transition-colors" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="Product image" class="max-w-full max-h-full object-contain shadow-2xl rounded-lg">
</div>
@endsection

@push('scripts')
<script>
function switchImage(btn, src) {
    document.getElementById('mainImg').src = src;
    btn.parentElement.querySelectorAll('button').forEach(b => {
        b.classList.remove('border-primary', 'shadow-md');
        b.classList.add('border-transparent', 'opacity-60');
    });
    btn.classList.add('border-primary', 'shadow-md');
    btn.classList.remove('border-transparent', 'opacity-60');
}

function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const max = parseInt(input.max);
    const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = newVal;
}

function switchTab(event, tabId) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.getElementById('tab-' + tabId).classList.remove('hidden');
    
    // UI state
    const btns = event.target.parentElement.querySelectorAll('button');
    btns.forEach(b => {
        b.classList.remove('after:bg-primary', 'active-tab-btn');
        b.classList.add('text-light', 'after:bg-transparent');
    });
    event.target.classList.add('active-tab-btn', 'after:bg-primary');
    event.target.classList.remove('text-light', 'after:bg-transparent');
}

// Lightbox
function openLightbox(src) {
    const overlay = document.getElementById('lightboxOverlay');
    document.getElementById('lightboxImg').src = src;
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.add('opacity-100'), 10);
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    const overlay = document.getElementById('lightboxOverlay');
    overlay.classList.remove('opacity-100');
    setTimeout(() => overlay.classList.add('hidden'), 300);
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// Sticky cart bar
const pdpForm = document.getElementById('pdpAddForm');
const stickyBar = document.getElementById('stickyCartBar');
if (pdpForm && stickyBar) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                stickyBar.classList.remove('translate-y-full');
            } else {
                stickyBar.classList.add('translate-y-full');
            }
        });
    }, { threshold: 0 });
    observer.observe(pdpForm);
}
</script>
<style>
.active-tab-btn {
    color: var(--dark) !important;
}
</style>
@endpush
