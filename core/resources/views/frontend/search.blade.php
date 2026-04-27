@extends('frontend.app')

@section('content')

@php
use App\Models\Setting;
use App\Models\Category;

$settings = Setting::first();
$currency = $settings?->currency ?? '৳';
@endphp

<!-- ================= LOADING ================= -->
<div class="loading-gym" id="loader">
    <div class="dumbbell-loader">
        <span></span>
    </div>
</div>

<div class="container py-4">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 page-header">

        <div>
            <h5 class="fw-bold mb-1 text-light">
                <i class="bi bi-search"></i>
                Search result for:
                <span class="text-danger power-glow">"{{ $query }}"</span>
            </h5>
            <small class="text-muted">{{ $packs->total() }} pack(s) found</small>
        </div>

        <div class="d-flex gap-1 mt-2 mt-md-0 flex-wrap align-items-center">

            <button class="btn btn-sm btn-outline-danger active" id="gridBtn">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </button>

            <button class="btn btn-sm btn-outline-danger" id="listBtn">
                <i class="bi bi-list-ul"></i>
            </button>

            <a href="{{ url('/') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-arrow-left-circle"></i>
            </a>

            <button class="btn btn-sm btn-danger mobile-filter-btn ms-2"
                data-bs-toggle="collapse" data-bs-target="#mobileFilter">
                <i class="bi bi-sliders"></i> Filter
            </button>

        </div>
    </div>

    <div class="row">

        <!-- ================= PRODUCTS ================= -->
        <div class="col-md-12">

            <div class="row g-3" id="productWrapper">

                @forelse($packs as $pack)

                <div class="col-6 col-sm-4 col-lg-3 product-item"
                    data-category="{{ $pack->category_id }}"
                    data-price="{{ $pack->pack_price }}">

                    <div class="card product-card h-100 shadow-sm border-0 position-relative">

                        <a href="{{ url('/pack/'.$pack->id) }}" class="stretched-link"></a>

                        <div class="product-img-wrapper position-relative overflow-hidden">

                            <img src="{{ config('app.storage_url').$pack->image }}"
                                class="card-img-top product-img"
                                alt="{{ $pack->name }}">

                            <div class="position-absolute top-0 start-0 w-100 p-1 d-flex justify-content-between">

                                @if($pack->discount > 0)
                                    <span class="badge bg-danger">
                                        -{{ $pack->discount }}%
                                    </span>
                                @endif

                                <span class="badge bg-dark ms-auto">
                                    {{ $pack->pack_price }} {{ $currency }}
                                </span>

                            </div>
                        </div>

                        <div class="card-body d-flex flex-column py-2">

                            <h6 class="fw-semibold text-light mb-1">
                                {{ Str::limit($pack->name, 50) }}
                            </h6>

                            <button class="btn btn-sm btn-danger mt-auto w-100 quick-view-btn"
                                data-id="{{ $pack->id }}">
                                <i class="bi bi-eye"></i> Quick View
                            </button>

                        </div>

                    </div>

                </div>

                @empty

                <div class="col-12 text-center text-light py-5">
                    <i class="bi bi-exclamation-circle text-danger" style="font-size:50px;"></i>
                    <h5 class="mt-2">No Packs Found</h5>
                </div>

                @endforelse

            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $packs->withQueryString()->links() }}
            </div>

        </div>
    </div>
</div>

<!-- ================= QUICK VIEW MODAL ================= -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content dark-modal" id="quickViewContent"></div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Hide loader after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1000);
        });

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
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                let id = btn.dataset.id;
                new bootstrap.Modal(document.getElementById('quickViewModal')).show();
            });
        });

        // Price filter
        function bindPriceFilter(rangeEl, valEl) {
            valEl.innerText = rangeEl.value;
            rangeEl.oninput = () => { 
                valEl.innerText = rangeEl.value; 
            };
            rangeEl.onchange = () => {
                const maxPrice = parseInt(rangeEl.value);
                document.querySelectorAll('.product-item').forEach(item => {
                    const price = parseInt(item.dataset.price);
                    item.style.display = price <= maxPrice ? 'block' : 'none';
                });
            };
        }
        
        bindPriceFilter(document.getElementById('priceRange'), document.getElementById('priceVal'));
        bindPriceFilter(document.getElementById('priceRangeMobile'), document.getElementById('priceValMobile'));

        // Category filter
        function bindCategoryFilter(selectEl) {
            selectEl.onchange = () => {
                const category = selectEl.value;
                document.querySelectorAll('.product-item').forEach(item => {
                    item.style.display = category === "" || item.dataset.category === category ? 'block' : 'none';
                });
            };
        }
        
        bindCategoryFilter(document.getElementById('categoryFilter'));
        bindCategoryFilter(document.getElementById('categoryFilterMobile'));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add parallax effect on scroll
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.page-header');
            if (parallax) {
                parallax.style.transform = 'translateY(' + scrolled * 0.3 + 'px)';
            }
        });
    </script>

 <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0F0F0F;
            --secondary: #E50914;
            --accent: #FF3B3B;
            --background: #121212;
            --text: #FFFFFF;
        }

        body {
            background: linear-gradient(135deg, var(--background) 0%, #1a1a1a 100%);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(229, 9, 20, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 59, 59, 0.1) 0%, transparent 50%);
            animation: pulseBackground 8s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes pulseBackground {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        /* Header Styles */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1a1a1a 100%);
            padding: 25px;
            border-radius: 15px;
            border: 2px solid var(--secondary);
            box-shadow: 0 0 30px rgba(229, 9, 20, 0.3);
            animation: slideDown 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(229, 9, 20, 0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .header-icon {
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .text-primary {
            color: var(--secondary) !important;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.5);
        }

        /* Button Styles */
        .btn-outline-primary {
            border-color: var(--secondary);
            color: var(--secondary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-outline-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: var(--secondary);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-outline-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-outline-primary:hover {
            color: white;
            border-color: var(--secondary);
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
        }

        .btn-outline-primary.active {
            background: var(--secondary);
            color: white;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
            animation: buttonPulse 1s ease;
        }

        @keyframes buttonPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .btn-primary:hover::after {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.4);
        }

        /* Filter Card */
        .filter-card {
            background: linear-gradient(135deg, var(--primary) 0%, #1a1a1a 100%);
            border: 2px solid var(--secondary);
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(229, 9, 20, 0.2);
            animation: fadeInLeft 0.6s ease-out;
            position: relative;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .filter-card h6 {
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .filter-card h6::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--secondary);
            animation: expandWidth 2s ease-in-out infinite;
        }

        @keyframes expandWidth {
            0%, 100% { width: 50px; }
            50% { width: 100px; }
        }

        .dark-input {
            background: var(--background);
            border: 1px solid var(--accent);
            color: var(--text);
            transition: all 0.3s ease;
        }

        .dark-input:focus {
            background: var(--background);
            border-color: var(--secondary);
            color: var(--text);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.3);
        }

        .form-range {
            accent-color: var(--secondary);
        }

        .form-range::-webkit-slider-thumb {
            background: var(--secondary);
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.5);
            cursor: pointer;
            animation: thumbPulse 2s infinite;
        }

        @keyframes thumbPulse {
            0%, 100% { box-shadow: 0 0 10px rgba(229, 9, 20, 0.5); }
            50% { box-shadow: 0 0 20px rgba(229, 9, 20, 0.8); }
        }

        /* Product Cards */
        .product-item {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .product-item:nth-child(1) { animation-delay: 0.1s; }
        .product-item:nth-child(2) { animation-delay: 0.2s; }
        .product-item:nth-child(3) { animation-delay: 0.3s; }
        .product-item:nth-child(4) { animation-delay: 0.4s; }
        .product-item:nth-child(5) { animation-delay: 0.5s; }
        .product-item:nth-child(6) { animation-delay: 0.6s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            background: linear-gradient(135deg, var(--primary) 0%, #1a1a1a 100%);
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--secondary), var(--accent), var(--secondary));
            border-radius: inherit;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .product-card:hover::before {
            opacity: 1;
            animation: borderGlow 2s linear infinite;
        }

        @keyframes borderGlow {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.4);
        }

        .product-img-wrapper {
            position: relative;
            height: 200px;
            background: var(--background);
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.1) rotate(2deg);
        }

        .badge-discount {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            animation: bounceIn 0.6s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .badge-price {
            background: rgba(15, 15, 15, 0.9);
            color: var(--secondary);
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            border: 1px solid var(--accent);
        }

        .product-title {
            color: var(--text);
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .product-card:hover .product-title {
            color: var(--secondary);
        }

        .quick-view-btn {
            position: relative;
            z-index: 2;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* List View */
        .list-view .product-item {
            width: 100% !important;
        }

        .list-view .product-card {
            display: flex;
            flex-direction: row;
        }

        .list-view .product-img-wrapper {
            width: 200px;
            min-width: 200px;
        }

        .list-view .card-body {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        /* Modal */
        .dark-modal {
            background: linear-gradient(135deg, var(--primary) 0%, #1a1a1a 100%);
            color: var(--text);
            border: 2px solid var(--secondary);
            border-radius: 15px;
            animation: modalZoom 0.3s ease;
        }

        @keyframes modalZoom {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .dark-modal .modal-header {
            border-bottom: 1px solid var(--secondary);
        }

        .dark-modal .modal-title {
            color: var(--secondary);
        }

        .btn-close {
            filter: invert(1);
        }

        /* Gym-specific Animations */
        @keyframes dumbbell {
            0%, 100% { transform: rotate(-15deg); }
            50% { transform: rotate(15deg); }
        }

        .header-icon i {
            animation: dumbbell 2s ease-in-out infinite;
        }

        /* Strength meter animation */
        @keyframes strengthMeter {
            0% { transform: scaleX(0); }
            100% { transform: scaleX(1); }
        }

        /* Loading Animation */
        .loading-gym {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--background);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-gym.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .dumbbell-loader {
            width: 100px;
            height: 50px;
            position: relative;
        }

        .dumbbell-loader::before,
        .dumbbell-loader::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 40px;
            background: var(--secondary);
            border-radius: 5px;
            animation: lift 1s ease-in-out infinite;
        }

        .dumbbell-loader::before {
            left: 0;
        }

        .dumbbell-loader::after {
            right: 0;
        }

        .dumbbell-loader span {
            position: absolute;
            left: 20px;
            right: 20px;
            top: 50%;
            height: 5px;
            background: var(--accent);
            transform: translateY(-50%);
        }

        @keyframes lift {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--background);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        /* Text animations */
        .text-muted {
            color: #888 !important;
        }

        /* No results */
        .text-danger {
            color: var(--secondary) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 15px;
            }
            
            .product-img-wrapper {
                height: 150px;
            }
        }

        /* Sticky top with offset */
        .sticky-top {
            top: 20px;
        }

        /* Card shadow animation */
        @keyframes shadowPulse {
            0%, 100% {
                box-shadow: 0 0 30px rgba(229, 9, 20, 0.2);
            }
            50% {
                box-shadow: 0 0 50px rgba(229, 9, 20, 0.4);
            }
        }

        .filter-card {
            animation: fadeInLeft 0.6s ease-out, shadowPulse 3s ease-in-out infinite;
        }

        /* Power glow effect */
        .power-glow {
            position: relative;
        }

        .power-glow::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(229, 9, 20, 0.3) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            animation: powerGlow 2s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes powerGlow {
            0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
        }
    </style>

@endsection