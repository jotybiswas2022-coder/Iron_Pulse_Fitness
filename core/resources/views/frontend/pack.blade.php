@extends('frontend.app')

@section('content')

@php
    $packDetails = (string) ($pack->details ?? '');
    $packDetails = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $packDetails);
    $packDetails = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $packDetails);
@endphp

{{-- Alerts --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show m-3 alert-success-custom" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show m-3 alert-danger-custom" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Product Page -->
<div class="product-page py-5">

    <!-- Pack Detail -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="product-card p-4 p-md-5">

                    <div class="row g-5 align-items-start">

                        <!-- Image -->
                        <div class="col-md-6 text-center position-relative animate-in delay-1">
                            <div class="image-wrapper position-relative power-pulse">
                                <img src="{{ $pack->image ? config('app.storage_url') . $pack->image : asset('frontend/img/product1.jpg') }}"
                                     alt="{{ $pack->name }}"
                                     class="img-fluid main-image">

                                @if($pack->discount)
                                <span class="discount-badge-3d">
                                    <i class="bi bi-lightning-charge-fill gym-icon"></i> {{ $pack->discount }}% OFF
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-md-6 animate-in delay-2">
                            <h2 class="fw-bold mt-2 product-title">
                                <i class="bi bi-award-fill gym-icon"></i> {{ $pack->name }}
                            </h2>

                            <!-- Price -->
                            <div class="price-box my-4">
                                @if($pack->discount)
                                    <div>
                                        <span class="original-price">
                                            <i class="bi bi-currency-dollar"></i> 
                                            {{ number_format($pack->pack_price, 2) }} {{ currency() }}
                                        </span>
                                    </div>
                                    <h4 class="discounted-price">
                                        <i class="bi bi-cash-stack"></i> 
                                        {{ number_format($pack->pack_price * (100 - $pack->discount)/100, 2) }} {{ currency() }}
                                    </h4>
                                @else
                                    <h4 class="discounted-price">
                                        <i class="bi bi-cash-stack"></i> 
                                        {{ number_format($pack->pack_price, 2) }} {{ currency() }}
                                    </h4>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="description-box mb-4">
                                {!! $packDetails !== '' ? $packDetails : '<p class="mb-0 text-muted">No details available.</p>' !!}
                            </div>

                            <!-- Buttons -->
                            @if (IsAddedToCart(auth()->id(), $pack->id))
                                <button class="btn btn-dark-theme btn-lg w-100">
                                    <i class="bi bi-cart-check me-2"></i> Already Added
                                </button>
                            @else
                                <a href="{{ url('/add_cart/'.$pack->id) }}" class="btn btn-dark-theme btn-lg w-100">
                                    <i class="bi bi-cart-plus me-2"></i> Purchase Pack
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Packs -->
    <div class="container mt-5 pt-3 animate-in delay-3">
        <h3 class="section-title">
            <i class="bi bi-fire gym-icon"></i> Related Packs
        </h3>

        @if($otherPacks->where('id', '!=', $pack->id)->count() > 0)
        <div class="swiper mySwiper mt-3">
            <div class="swiper-wrapper">

                @foreach($otherPacks as $item)
                @if($item->id != $pack->id)
                <div class="swiper-slide">
                    <div class="related-card rounded-3 text-center p-3">
                        <a href="{{ url('/pack/'.$item->id) }}" class="text-decoration-none text-light">
                            <div class="related-img-wrapper position-relative mb-2">
                                <img src="{{ config('app.storage_url') }}{{ $item->image }}"
                                     class="img-fluid related-img">

                                @if($item->discount)
                                <span class="discount-badge-small">
                                    <i class="bi bi-lightning-fill"></i> {{ $item->discount }}% OFF
                                </span>
                                @endif
                            </div>

                            <h6 class="fw-semibold mb-1">
                                {{ $item->name }}
                            </h6>
                        </a>

                        <div class="mb-2">
                            @if($item->discount)
                                <span class="old-price small">
                                    {{ number_format($item->pack_price,2) }} {{ currency() }}
                                </span> 
                                <span class="new-price">
                                    {{ number_format($item->pack_price * (100 - $item->discount)/100,2) }} {{ currency() }}
                                </span>
                            @else
                                <span class="new-price">
                                    {{ number_format($item->pack_price,2) }} {{ currency() }}
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


<!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Initialize Swiper
        const swiper = new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },
            },
        });

        // Add to Cart Function
        function addToCart() {
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            
            // Change button text
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Adding...';
            btn.disabled = true;

            // Simulate adding to cart
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-cart-check me-2"></i> Already Added';
                
                // Show success alert
                const alert = document.getElementById('successAlert');
                alert.classList.remove('hidden');
                
                // Hide alert after 3 seconds
                setTimeout(() => {
                    alert.classList.add('hidden');
                }, 3000);
            }, 1000);
        }

        // Close Alert Function
        function closeAlert(alertId) {
            document.getElementById(alertId).classList.add('hidden');
        }

        // Add particle effect on hover
        document.querySelectorAll('.related-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });

        // Smooth scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.related-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>

     <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #0F0F0F;
            --secondary: #E50914;
            --accent: #FF3B3B;
            --background: #121212;
            --text: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0F0F0F 0%, #121212 50%, #1a1a1a 100%);
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
            pointer-events: none;
            z-index: 0;
            animation: pulseGlow 4s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }

        /* Header */
        .gym-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1a1a1a 100%);
            border-bottom: 3px solid var(--secondary);
            box-shadow: 0 4px 20px rgba(229, 9, 20, 0.3);
            position: relative;
            z-index: 10;
        }

        .gym-logo {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(229, 9, 20, 0.5);
            animation: logoGlow 2s ease-in-out infinite;
        }

        @keyframes logoGlow {
            0%, 100% { filter: drop-shadow(0 0 10px rgba(229, 9, 20, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(229, 9, 20, 0.8)); }
        }

        /* Alert Styles */
        .alert-success-custom {
            background: linear-gradient(135deg, #1a4d2e 0%, #2d5f3f 100%);
            border-left: 5px solid #4ade80;
            color: var(--text);
            border-radius: 10px;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, #4d1a1a 0%, #5f2d2d 100%);
            border-left: 5px solid var(--secondary);
            color: var(--text);
            border-radius: 10px;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Product Card */
        .product-card {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.95) 0%, rgba(26, 26, 26, 0.95) 100%);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 20px;
            box-shadow: 
                0 10px 40px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(229, 9, 20, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(229, 9, 20, 0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .product-card:hover {
            border-color: var(--secondary);
            box-shadow: 
                0 15px 50px rgba(0, 0, 0, 0.7),
                0 0 40px rgba(229, 9, 20, 0.4);
            transform: translateY(-5px);
        }

        /* Image Container */
        .image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            border: 3px solid rgba(229, 9, 20, 0.3);
            transition: all 0.4s ease;
        }

        .image-wrapper::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: linear-gradient(45deg, transparent 30%, rgba(229, 9, 20, 0.2) 50%, transparent 70%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translate(-100%, -100%); }
            100% { transform: translate(100%, 100%); }
        }

        .main-image {
            border-radius: 12px;
            transition: all 0.4s ease;
            filter: brightness(0.9);
        }

        .image-wrapper:hover .main-image {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .power-pulse {
            animation: powerPulse 2s ease-in-out infinite;
        }

        @keyframes powerPulse {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
            }
            50% { 
                box-shadow: 0 0 40px rgba(229, 9, 20, 0.6);
            }
        }

        /* Discount Badge */
        .discount-badge-3d {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: var(--text);
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 900;
            font-size: 1.1rem;
            box-shadow: 
                0 5px 15px rgba(229, 9, 20, 0.5),
                inset 0 -2px 5px rgba(0, 0, 0, 0.3);
            animation: badgePulse 1.5s ease-in-out infinite;
            z-index: 10;
        }

        @keyframes badgePulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 5px 15px rgba(229, 9, 20, 0.5);
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 8px 25px rgba(229, 9, 20, 0.8);
            }
        }

        .discount-badge-small {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: var(--text);
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            box-shadow: 0 3px 10px rgba(229, 9, 20, 0.5);
            z-index: 2;
        }

        /* Product Title */
        .product-title {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text) 0%, #cccccc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: titleGlow 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3)); }
            50% { filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5)); }
        }

        /* Gym Icons */
        .gym-icon {
            color: var(--secondary);
            animation: iconPulse 2s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Price Box */
        .price-box {
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.1) 0%, rgba(255, 59, 59, 0.1) 100%);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 15px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .price-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.2), transparent);
            animation: priceShine 2s infinite;
        }

        @keyframes priceShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .original-price {
            text-decoration: line-through;
            color: #888;
            font-size: 1.2rem;
            opacity: 0.7;
        }

        .discounted-price {
            color: var(--secondary);
            font-size: 2.5rem;
            font-weight: 900;
            text-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
            animation: priceGlow 2s ease-in-out infinite;
        }

        @keyframes priceGlow {
            0%, 100% { text-shadow: 0 0 20px rgba(229, 9, 20, 0.5); }
            50% { text-shadow: 0 0 30px rgba(229, 9, 20, 0.8); }
        }

        /* Description Box */
        .description-box {
            background: rgba(255, 255, 255, 0.03);
            border-left: 4px solid var(--secondary);
            border-radius: 10px;
            padding: 20px;
            line-height: 1.8;
        }

        /* Buttons */
        .btn-dark-theme {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: var(--text);
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 
                0 5px 20px rgba(229, 9, 20, 0.4),
                inset 0 -3px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-dark-theme::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .btn-dark-theme:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-dark-theme:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 30px rgba(229, 9, 20, 0.6),
                inset 0 -3px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-dark-theme:active {
            transform: translateY(-1px);
            box-shadow: 0 3px 15px rgba(229, 9, 20, 0.4);
        }

        /* Section Title */
        .section-title {
            font-size: 2.5rem;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 40px;
            background: linear-gradient(135deg, var(--text) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            padding-bottom: 20px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--secondary), transparent);
            animation: lineGrow 2s ease-in-out infinite;
        }

        @keyframes lineGrow {
            0%, 100% { width: 100px; }
            50% { width: 200px; }
        }

        /* Related Cards */
        .related-card {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.9) 0%, rgba(26, 26, 26, 0.9) 100%);
            border: 2px solid rgba(229, 9, 20, 0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .related-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.1), transparent);
            transition: left 0.5s;
        }

        .related-card:hover::before {
            left: 100%;
        }

        .related-card:hover {
            border-color: var(--secondary);
            transform: translateY(-10px);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(229, 9, 20, 0.3);
        }

        .related-img-wrapper {
            overflow: hidden;
            border-radius: 10px;
            height: 200px;
        }

        .related-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            filter: brightness(0.9);
        }

        .related-card:hover .related-img {
            transform: scale(1.15);
            filter: brightness(1.1);
        }

        .old-price {
            text-decoration: line-through;
            color: #888;
            margin-right: 10px;
        }

        .new-price {
            color: var(--secondary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Swiper Custom Styles */
        .swiper {
            padding: 20px 0 50px 0;
        }

        .swiper-button-next,
        .swiper-button-prev {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 5px 20px rgba(229, 9, 20, 0.5);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px;
            color: var(--text);
            font-weight: 900;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.8);
            transform: scale(1.1);
        }

        /* Animations */
        .animate-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Strength Bars Animation */
        .strength-indicator {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .strength-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            border-radius: 10px;
            animation: strengthGrow 2s ease-out;
        }

        @keyframes strengthGrow {
            from { width: 0; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .discounted-price {
                font-size: 2rem;
            }

            .gym-logo {
                font-size: 1.5rem;
            }
        }
    </style>

@endsection