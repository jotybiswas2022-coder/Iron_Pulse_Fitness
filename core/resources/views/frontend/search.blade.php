@extends('frontend.app')

@section('content')

@php
use App\Models\Setting;
$settings = Setting::first();
$currency = $settings?->currency ?? '৳';
@endphp

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 page-header">
        <div>
            <h5 class="fw-bold mb-1 text-light">
                <span class="header-icon"><i class="bi bi-search"></i></span>
                Search result for:
                <span class="text-primary">"{{ $query }}"</span>
            </h5>
            <small class="text-muted">{{ $products->total() }} product(s) found</small>
        </div>

        <div class="d-flex gap-1 mt-2 mt-md-0 flex-wrap align-items-center">
            <button class="btn btn-sm btn-outline-primary active" id="gridBtn">
                <i class="bi bi-grid"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" id="listBtn">
                <i class="bi bi-list"></i>
            </button>
            <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <button class="btn btn-sm btn-primary mobile-filter-btn ms-2" data-bs-toggle="collapse" data-bs-target="#mobileFilter">
                <i class="bi bi-sliders"></i> Filter
            </button>
        </div>
    </div>

    <div class="row">

        <!-- Filter Sidebar -->
        <div class="col-md-3 mb-3 d-none d-md-block">
            <div class="card p-3 shadow-sm sticky-top filter-card">
                <h6 class="fw-bold mb-3">Filter Products</h6>

                <div class="mb-3">
                    <label class="form-label small">Category</label>
                    <select id="categoryFilter" class="form-select form-select-sm dark-input">
                        <option value="">All Categories</option>
                        @foreach(App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small">
                        Max Price: <span id="priceVal">0</span> {{ $currency }}
                    </label>
                    <input type="range" min="0" max="300000" step="1000" class="form-range" id="priceRange">
                </div>
            </div>
        </div>

        <!-- Mobile Filter Collapse -->
        <div class="col-12 mb-3 d-md-none collapse" id="mobileFilter">
            <div class="card p-3 shadow-sm filter-card">
                <h6 class="fw-bold mb-3">Filter Products</h6>

                <div class="mb-3">
                    <label class="form-label small">Category</label>
                    <select id="categoryFilterMobile" class="form-select form-select-sm dark-input">
                        <option value="">All Categories</option>
                        @foreach(App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small">
                        Max Price: <span id="priceValMobile">0</span> {{ $currency }}
                    </label>
                    <input type="range" min="0" max="300000" step="1000" class="form-range" id="priceRangeMobile">
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-9">
            <div class="row g-3" id="productWrapper">

                @forelse($products as $product)
                <div class="col-6 col-sm-4 col-lg-3 product-item"
                    data-category="{{ $product->category_id }}"
                    data-price="{{ $product->price }}">
                    <div class="card product-card h-100 border-0 shadow-sm position-relative">

                        <a href="{{ url('/product/'.$product->id) }}" class="stretched-link"></a>

                        <div class="position-relative overflow-hidden product-img-wrapper">
                            <img src="{{ config('app.storage_url') }}{{ $product->image }}"
                                class="card-img-top product-img"
                                alt="{{ $product->name }}">

                            <div class="d-flex justify-content-between position-absolute top-0 start-0 w-100 p-1">
                                @if($product->discount > 0)
                                <span class="badge badge-discount">-{{ $product->discount }}%</span>
                                @endif
                                <span class="badge badge-price ms-auto">{{ $product->price }} {{ $currency }}</span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column py-2">
                            <h6 class="fw-semibold mb-1 product-title">{{ Str::limit($product->name,50) }}</h6>

                            @if($product->stock > 0)
                            <small class="stock-in mb-2 d-block"><i class="bi bi-check-circle"></i> In Stock</small>
                            @else
                            <small class="stock-out mb-2 d-block"><i class="bi bi-x-circle"></i> Out of Stock</small>
                            @endif

                            <button class="btn btn-sm btn-primary mt-auto quick-view-btn w-100"
                                data-id="{{ $product->id }}">
                                <i class="bi bi-eye"></i> Quick View
                            </button>
                        </div>

                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4 text-light">
                    <i class="bi bi-search text-danger" style="font-size:50px;"></i>
                    <h5 class="fw-bold text-danger mt-2 mb-1">No product found</h5>
                    <p class="text-muted small">Try searching with different keywords.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm mt-1">
                        <i class="bi bi-house"></i> Continue Shopping
                    </a>
                </div>
                @endforelse

            </div>

            @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dark-modal" id="quickViewContent"></div>
    </div>
</div>

<style>
/* ─── Dark Theme Variables ─── */
:root {
    --primary: #0f172a;
    --primary-deep: #020617;
    --accent: #3b82f6;
    --accent-hover: #2563eb;
    --accent-glow: rgba(59, 130, 246, 0.35);
    --accent-soft: rgba(59, 130, 246, 0.12);
    --accent-border: rgba(59,130,246,.2);
    --text-light: #e2e8f0;
    --text-muted: #94a3b8;
    --card-bg: #0f172a;
    --surface: #1e293b;
}

body { background: var(--primary-deep); color: var(--text-light); font-family:'Inter',sans-serif; min-height:100vh; }

/* Filter card */
.filter-card {
    background: var(--primary);
    border:1px solid var(--accent-border);
    border-radius:16px;
    color: var(--text-light);
}

/* Inputs */
.dark-input {
    background: var(--primary-deep);
    color: var(--text-light);
    border:1px solid var(--accent-border);
    border-radius:10px;
}
.dark-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

/* Product card */
.product-card {
    background: var(--card-bg);
    border-radius:16px;
    border:1px solid var(--accent-border);
    transition: all .3s ease;
}
.product-card:hover {
    transform: translateY(-6px);
    border-color: var(--accent);
    box-shadow: 0 20px 50px rgba(0,0,0,.5), 0 0 30px var(--accent-glow);
}
.product-img-wrapper { border-radius:16px 16px 0 0; overflow:hidden; position:relative; }
.product-img-wrapper::after { content:''; position:absolute; bottom:0; left:0; right:0; height:50%; background: linear-gradient(to top,var(--card-bg),transparent); pointer-events:none; }
.product-img { width:100%; height:200px; object-fit:cover; transition:.3s; }
.product-card:hover .product-img { transform:scale(1.08); }

/* Badges */
.badge-discount { background:#22c55e; color:#fff; font-weight:700; font-size:.7rem; padding:4px 8px; border-radius:8px; }
.badge-price { background: var(--accent); color:#fff; font-weight:700; font-size:.72rem; padding:4px 8px; border-radius:8px; }

/* Card Body */
.product-title { font-size:.85rem; color:var(--text-light); line-height:1.35; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

/* Quick view button */
.quick-view-btn { border-radius:10px; font-size:.78rem; padding:7px 0; }

/* List view */
.list-view .product-item { flex:0 0 100%; max-width:100%; }
.list-view .product-card { flex-direction: row; border-radius:14px; }
.list-view .product-img-wrapper { width:200px; min-width:200px; border-radius:14px 0 0 14px; }
.list-view .product-img { height:100%; min-height:180px; }
.list-view .card-body { display:flex; flex-direction:column; justify-content:center; }

/* Stock */
.stock-in { color:#22c55e; font-size:.78rem; }
.stock-out { color:#ef4444; font-size:.78rem; }

/* Modal */
.dark-modal { background:var(--primary); color:var(--text-light); border:1px solid var(--accent-border); border-radius:18px; }
.dark-modal .modal-header { border-bottom:1px solid var(--accent-border); padding:18px 24px; }
.dark-modal .modal-title { color: var(--accent); font-weight:700; }
.dark-modal .btn-close { filter:invert(1); }

/* Mobile */
@media (max-width:767.98px) { .product-img { height:150px; } .mobile-filter-btn { display:inline-flex; } }

</style>

<script>
// Grid/List toggle
document.getElementById('listBtn').onclick = function() {
    document.getElementById('productWrapper').classList.add('list-view');
    this.classList.add('active');
    document.getElementById('gridBtn').classList.remove('active');
};
document.getElementById('gridBtn').onclick = function() {
    document.getElementById('productWrapper').classList.remove('list-view');
    this.classList.add('active');
    document.getElementById('listBtn').classList.remove('active');
};

// Quick View
document.querySelectorAll('.quick-view-btn').forEach(btn => {
    btn.addEventListener('click', ()=> {
        let id = btn.dataset.id;
        document.getElementById('quickViewContent').innerHTML =
        `<div class="modal-header">
            <h5 class="modal-title">Product #${id} Quick View</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Product details will load via AJAX here...</p>
        </div>`;
        new bootstrap.Modal(document.getElementById('quickViewModal')).show();
    });
});

// Price filter
function bindPriceFilter(rangeEl,valEl){
    valEl.innerText = rangeEl.value;
    rangeEl.oninput = ()=>{ valEl.innerText = rangeEl.value; };
    rangeEl.onchange = ()=>{
        const maxPrice = parseInt(rangeEl.value);
        document.querySelectorAll('.product-item').forEach(item=>{
            const price = parseInt(item.dataset.price);
            item.style.display = price <= maxPrice ? 'block':'none';
        });
    };
}
bindPriceFilter(document.getElementById('priceRange'), document.getElementById('priceVal'));
bindPriceFilter(document.getElementById('priceRangeMobile'), document.getElementById('priceValMobile'));

// Category filter
function bindCategoryFilter(selectEl){
    selectEl.onchange = ()=>{
        const category = selectEl.value;
        document.querySelectorAll('.product-item').forEach(item=>{
            item.style.display = category==="" || item.dataset.category===category ? 'block':'none';
        });
    };
}
bindCategoryFilter(document.getElementById('categoryFilter'));
bindCategoryFilter(document.getElementById('categoryFilterMobile'));
</script>

@endsection