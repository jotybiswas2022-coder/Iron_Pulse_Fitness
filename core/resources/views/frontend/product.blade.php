@extends('frontend.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3 alert-success-custom" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3 alert-danger-custom" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="product-page py-5">

    <!-- Product Detail -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="product-card p-4 p-md-5">

                    <div class="row g-5 align-items-start">

                        <!-- Image -->
                        <div class="col-md-6 text-center position-relative animate-in delay-1">
                            <div class="image-wrapper position-relative">
                                <img src="{{ config('app.storage_url') }}{{ $product->image }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid main-image">
                                @if($product->discount)
                                    <span class="discount-badge-3d">{{ $product->discount }}% OFF</span>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-md-6 animate-in delay-2">
                            <h2 class="fw-bold mt-2 product-title">{{ $product->name }}</h2>

                            <!-- Price -->
                            <div class="price-box my-4">
                                @if($product->discount)
                                    <div>
                                        <span class="original-price">
                                            {{ number_format($product->price, 2) }} {{ currency() }}
                                        </span>
                                    </div>
                                    <h4 class="discounted-price">
                                        {{ number_format($product->price * (100 - $product->discount)/100, 2) }} {{ currency() }}
                                    </h4>
                                @else
                                    <h4 class="final-price">
                                        {{ number_format($product->price, 2) }} {{ currency() }}
                                    </h4>
                                @endif
                            </div>

                            <!-- Stock -->
                            @if($product->stock > 0)
                                <div class="stock-status mb-3">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width:100%"></div>
                                    </div>
                                    <small class="fw-semibold mt-1 d-block text-light">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        In Stock ({{ $product->stock }} available)
                                    </small>
                                </div>
                            @else
                                <div class="stock-status mb-3">
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" style="width:100%"></div>
                                    </div>
                                    <small class="fw-semibold mt-1 d-block text-danger">
                                        Out of Stock
                                    </small>
                                </div>
                            @endif

                            <!-- Description -->
                            <div class="description-box mb-4">
                                {!! $product->details !!}
                            </div>

                            <!-- Buttons -->
                            @if($product->stock <= 0)
                                <button class="btn btn-dark-theme btn-lg w-100" disabled>
                                    <i class="bi bi-x-circle me-2"></i> Out of Stock
                                </button>
                            @elseif (IsAddedToCart(auth()->id(), $product->id))
                                <button class="btn btn-dark-theme btn-lg w-100" disabled>
                                    <i class="bi bi-cart-check me-2"></i> Already Added
                                </button>
                            @else
                                <a href="{{ url('/add_cart/'.$product->id) }}" class="btn btn-dark-theme btn-lg w-100">
                                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="container mt-5 pt-3 animate-in delay-3">
        <h3 class="section-title mb-4">Related Products</h3>

        @if($otherProducts->where('id', '!=', $product->id)->count() > 0)
            <div class="swiper mySwiper mt-3">
                <div class="swiper-wrapper">
                    @foreach($otherProducts as $item)
                        @if($item->id != $product->id)
                            <div class="swiper-slide">
                                <div class="related-card rounded-3 text-center p-3">
                                    <a href="{{ url('/product/'.$item->id) }}" class="text-decoration-none text-light">
                                        <div class="related-img-wrapper position-relative mb-2">
                                            <img src="{{ config('app.storage_url') }}{{ $item->image }}"
                                                 class="img-fluid related-img">
                                            @if($item->discount)
                                                <span class="discount-badge-small">{{ $item->discount }}% OFF</span>
                                            @endif
                                        </div>
                                        <h6 class="fw-semibold mb-1">{{ $item->name }}</h6>
                                    </a>
                                    <div class="mb-2">
                                        @if($item->discount)
                                            <span class="old-price small">
                                                {{ number_format($item->price,2) }} {{ currency() }}
                                            </span> 
                                            <span class="new-price">
                                                 {{ number_format($item->price * (100 - $item->discount)/100,2) }} {{ currency() }}
                                            </span>
                                        @else
                                            <span class="new-price">
                                                {{ number_format($item->price,2) }} {{ currency() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @endif
    </div>

</div>

<!-- Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 4,
    spaceBetween: 25,
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    breakpoints: {
        0:{slidesPerView:1, spaceBetween:15},
        576:{slidesPerView:2, spaceBetween:20},
        768:{slidesPerView:3, spaceBetween:20},
        992:{slidesPerView:4, spaceBetween:25},
    },
});

// Scroll Animation
const observerOptions = { threshold: 0.1 };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.animate-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.7s ease';
    observer.observe(el);
});
</script>

<style>
/* ===== Variables ===== */
:root {
    --primary: #0f172a;
    --primary-deep: #020617;
    --accent: #3b82f6;
    --accent-hover: #2563eb;
    --accent-glow: rgba(59, 130, 246, 0.4);
    --accent-border: rgba(59, 130, 246, 0.15);
    --text-main: #e5e7eb;
    --text-muted: #94a3b8;
    --text-desc: #cbd5e1;
}

/* ===== PAGE ===== */
.product-page{ background: var(--primary-deep); min-height:100vh; }

/* ===== ALERTS ===== */
.alert-success-custom{ background: rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.3); color:#4ade80; border-radius:12px; }
.alert-danger-custom{ background: rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3); color:#f87171; border-radius:12px; }

/* ===== CARD ===== */
.product-card{
    background: var(--primary);
    color: var(--text-main);
    border-radius:24px;
    border:1px solid var(--accent-border);
    box-shadow:0 25px 50px rgba(0,0,0,.5),0 0 80px rgba(59,130,246,.04);
    position:relative;
    overflow:hidden;
}
.product-card::before{
    content:'';
    position:absolute; top:-1px; left:0; right:0; height:3px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
}

/* ===== IMAGE ===== */
.image-wrapper{ border-radius:20px; overflow:hidden; background: var(--primary-deep); position:relative; border:1px solid var(--accent-border); }
.image-wrapper::after{ content:''; position:absolute; inset:0; border-radius:20px; background: linear-gradient(180deg, transparent 60%, rgba(2,6,23,.4)); pointer-events:none; }
.main-image{ transition: transform .5s cubic-bezier(.25,.46,.45,.94); width:100%; display:block; }
.main-image:hover{ transform:scale(1.08); }

/* ===== BADGE ===== */
.discount-badge-3d{
    position:absolute; top:15px; left:15px; background:linear-gradient(135deg,var(--accent),#1d4ed8);
    color:#fff; font-weight:700; padding:10px 16px; border-radius:14px; font-size:.85rem;
    z-index:5; box-shadow:0 8px 24px var(--accent-glow); animation: badge-pulse 2s ease-in-out infinite;
}
@keyframes badge-pulse{0%,100%{box-shadow:0 8px 24px var(--accent-glow);}50%{box-shadow:0 8px 32px rgba(59,130,246,.6);}}

/* ===== TITLE ===== */
.product-title{ color:#fff; font-size:1.75rem; font-weight:800; line-height:1.3; letter-spacing:-0.5px; }

/* ===== PRICE ===== */
.price-box{
    background: var(--primary-deep); padding:18px 24px; border-radius:16px; display:inline-block; border:1px solid var(--accent-border); position:relative;
}
.price-box::before{ content:''; position:absolute; inset:-1px; border-radius:16px; background:linear-gradient(135deg, rgba(59,130,246,.2), transparent); z-index:0; pointer-events:none; }
.original-price{ color:var(--text-muted); text-decoration:line-through; font-size:.95rem; position:relative; z-index:1; }
.discounted-price, .final-price{ color:var(--accent); font-weight:800; font-size:1.6rem; position:relative; z-index:1; }

/* ===== STOCK ===== */
.stock-status .progress{ height:6px; background:rgba(255,255,255,.06); border-radius:10px; overflow:hidden; }
.stock-status .progress-bar.bg-success{ background: linear-gradient(90deg,#22c55e,#4ade80)!important; box-shadow:0 0 12px rgba(34,197,94,.4); }

/* ===== DESCRIPTION ===== */
.description-box{ max-height:220px; overflow-y:auto; padding:18px; background: var(--primary-deep); border-radius:16px; color: var(--text-desc); border:1px solid var(--accent-border); line-height:1.7; font-size:.92rem; }
.description-box::-webkit-scrollbar{ width:5px; }
.description-box::-webkit-scrollbar-thumb{ background:var(--accent); border-radius:10px; }
.description-box::-webkit-scrollbar-track{ background:transparent; }

/* ===== BUTTON ===== */
.btn-dark-theme{
    background: linear-gradient(135deg, var(--accent), #1d4ed8); color:#fff; font-weight:700; border-radius:14px; border:none; padding:14px 28px; font-size:1.05rem; transition:all .35s cubic-bezier(.25,.46,.45,.94); position:relative; overflow:hidden;
}
.btn-dark-theme::before{ content:''; position:absolute; top:0; left:-100%; width:100%; height:100%; background:linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent); transition:left .6s; }
.btn-dark-theme:hover::before{ left:100%; }
.btn-dark-theme:hover{ background:linear-gradient(135deg,var(--accent-hover),#1e40af); transform:translateY(-3px); box-shadow:0 12px 30px var(--accent-glow); }
.btn-dark-theme:disabled{ background:#1e293b; color:var(--text-muted); transform:none; box-shadow:none; cursor:not-allowed; }
.btn-dark-theme:disabled::before{ display:none; }

/* ===== FEATURES ===== */
.feature-strip{ display:flex; gap:12px; flex-wrap:wrap; margin-top:16px; }
.feature-item{ display:flex; align-items:center; gap:8px; background: var(--primary-deep); border:1px solid var(--accent-border); border-radius:12px; padding:8px 14px; font-size:.82rem; color:var(--text-muted); }
.feature-item i{ color:var(--accent); font-size:1rem; }

/* ===== RELATED SECTION ===== */
.section-title{ color:#fff; font-weight:800; font-size:1.6rem; position:relative; display:inline-block; }
.section-title::after{ content:''; position:absolute; bottom:-8px; left:0; width:50px; height:3px; background:var(--accent); border-radius:10px; }

/* ===== RELATED CARDS ===== */
.related-card{ background:var(--primary); border:1px solid var(--accent-border); border-radius:18px; transition:all .4s cubic-bezier(.25,.46,.45,.94); min-height:360px; overflow:hidden; }
.related-card:hover{ transform:translateY(-8px); box-shadow:0 20px 50px rgba(0,0,0,.6),0 0 40px rgba(59,130,246,.08); border-color:rgba(59,130,246,.3); }
.related-img-wrapper{ border-radius:14px; overflow:hidden; background: var(--primary-deep); }
.related-img{ height:220px; width:100%; object-fit:cover; border-radius:14px; transition: transform .4s; }
.related-card:hover .related-img{ transform:scale(1.06); }
.discount-badge-small{ position:absolute; top:10px; left:10px; background:linear-gradient(135deg,var(--accent),#1d4ed8); color:#fff; padding:5px 10px; border-radius:10px; font-size:.75rem; font-weight:700; z-index:2; }
.old-price{ color:var(--text-muted); text-decoration:line-through; font-size:.85rem; }
.new-price{ color:var(--accent); font-weight:700; font-size:1rem; }

/* ===== SWIPER NAV ===== */
.swiper-button-next, .swiper-button-prev{
    color:var(--accent)!important; background:var(--primary); width:44px; height:44px; border-radius:12px; border:1px solid var(--accent-border); transition:.3s;
}
.swiper-button-next::after, .swiper-button-prev::after{ font-size:16px; font-weight:900; }
.swiper-button-next:hover, .swiper-button-prev:hover{ background:var(--accent); color:#fff!important; box-shadow:0 8px 20px var(--accent-glow); }

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp{ from{opacity:0; transform:translateY(30px);} to{opacity:1; transform:translateY(0);} }
.animate-in{ animation:fadeInUp .7s ease forwards; }
.delay-1{ animation-delay:.1s; }
.delay-2{ animation-delay:.2s; }
.delay-3{ animation-delay:.3s; }

/* ===== SCROLLBAR ===== */
::-webkit-scrollbar{ width:8px; }
::-webkit-scrollbar-track{ background: var(--primary-deep); }
::-webkit-scrollbar-thumb{ background: var(--accent); border-radius:10px; }

/* ===== RESPONSIVE ===== */
@media(max-width:767px){ .product-title{ font-size:1.35rem; } .discounted-price,.final-price{ font-size:1.3rem; } .product-card{ border-radius:18px; } }
</style>

@endsection