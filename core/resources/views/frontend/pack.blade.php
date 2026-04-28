@extends('frontend.app')

@section('content')

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
                                {!! $pack->details ?? '<p class="mb-0 text-muted">No details available.</p>' !!}
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <script>
        document.documentElement.classList.add('js');

        // Swiper Initialization
        if (document.querySelector('.mySwiper') && typeof Swiper !== 'undefined') {
            new Swiper(".mySwiper", {
                slidesPerView: 4,
                spaceBetween: 25,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                breakpoints: {
                    0: { slidesPerView: 1, spaceBetween: 15 },
                    576: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 3, spaceBetween: 20 },
                    992: { slidesPerView: 4, spaceBetween: 25 },
                },
            });
        }

        // Scroll animation (safe fallback: keep content visible if observer is unavailable)
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-in').forEach(el => {
                observer.observe(el);
            });
        } else {
            document.querySelectorAll('.animate-in').forEach(el => {
                el.classList.add('is-visible');
            });
        }

        // Auto-hide alert after 5 seconds
        if (typeof bootstrap !== 'undefined') {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        }

        // Add muscle flex animation to buttons on hover
        document.querySelectorAll('.btn-dark-theme').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.animation = 'muscleFlex 0.5s ease-in-out';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.animation = '';
            });
        });

        // Power pulse effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const powerElements = document.querySelectorAll('.power-pulse');
            
            powerElements.forEach(el => {
                const speed = 0.5;
                el.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    </script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    
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
            background: var(--background);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            z-index: -1;
            animation: pulseBackground 8s ease-in-out infinite;
        }

        @keyframes pulseBackground {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Custom Alerts */
        .alert-success-custom {
            background: linear-gradient(135deg, #1a3a1a 0%, #2d5a2d 100%);
            border: 2px solid #4caf50;
            color: #fff;
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, #3a1a1a 0%, #5a2d2d 100%);
            border: 2px solid var(--secondary);
            color: #fff;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Product Card */
        .product-card {
            background: linear-gradient(145deg, rgba(25, 25, 25, 0.95) 0%, rgba(15, 15, 15, 0.95) 100%);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.5),
                inset 0 0 30px rgba(229, 9, 20, 0.1);
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(229, 9, 20, 0.1),
                transparent
            );
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        /* Image Wrapper */
        .image-wrapper {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.4);
            animation: floatImage 4s ease-in-out infinite;
        }

        @keyframes floatImage {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-10px) scale(1.02); }
        }

        .main-image {
            width: 100%;
            height: auto;
            border-radius: 15px;
            transition: transform 0.5s ease;
            border: 3px solid rgba(229, 9, 20, 0.4);
        }

        .image-wrapper:hover .main-image {
            transform: scale(1.05);
        }

        /* Discount Badge */
        .discount-badge-3d {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: #fff;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 
                0 8px 20px rgba(229, 9, 20, 0.6),
                inset 0 -3px 10px rgba(0, 0, 0, 0.3);
            animation: pulseBadge 2s ease-in-out infinite;
            z-index: 10;
        }

        @keyframes pulseBadge {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(3deg); }
        }

        /* Product Title */
        .product-title {
            font-size: 2.5rem;
            background: linear-gradient(90deg, #fff 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
            animation: titlePulse 3s ease-in-out infinite;
        }

        @keyframes titlePulse {
            0%, 100% { text-shadow: 0 0 10px rgba(229, 9, 20, 0.5); }
            50% { text-shadow: 0 0 20px rgba(229, 9, 20, 0.8); }
        }

        /* Price Box */
        .price-box {
            background: rgba(229, 9, 20, 0.1);
            border: 2px solid var(--secondary);
            border-radius: 12px;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: priceShine 3s infinite;
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

        .discounted-price,
        .final-price {
            color: var(--accent);
            font-weight: bold;
            font-size: 2.5rem;
            text-shadow: 0 0 20px rgba(255, 59, 59, 0.5);
            animation: priceGlow 2s ease-in-out infinite;
        }

        @keyframes priceGlow {
            0%, 100% { text-shadow: 0 0 20px rgba(255, 59, 59, 0.5); }
            50% { text-shadow: 0 0 30px rgba(255, 59, 59, 0.8); }
        }

        /* Description Box */
        .description-box {
            background: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--accent);
            padding: 20px;
            border-radius: 8px;
            line-height: 1.8;
            animation: fadeInUp 1s ease-out;
        }

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

        /* Button */
        .btn-dark-theme {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 15px 40px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.4);
            transition: all 0.3s ease;
        }

        .btn-dark-theme::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-dark-theme:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-dark-theme:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.6);
        }

        .btn-dark-theme:disabled {
            background: linear-gradient(135deg, #555 0%, #333 100%);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-dark-theme i {
            position: relative;
            z-index: 1;
            animation: iconBounce 2s ease-in-out infinite;
        }

        @keyframes iconBounce {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }

        /* Section Title */
        .section-title {
            font-size: 2rem;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            animation: lineExpand 2s ease-in-out infinite;
        }

        @keyframes lineExpand {
            0%, 100% { width: 80px; }
            50% { width: 120px; }
        }

        /* Related Cards */
        .related-card {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.9) 0%, rgba(20, 20, 20, 0.9) 100%);
            border: 2px solid rgba(229, 9, 20, 0.2);
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .related-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .related-card:hover::before {
            left: 100%;
        }

        .related-card:hover {
            transform: translateY(-10px) scale(1.03);
            border-color: var(--accent);
            box-shadow: 
                0 20px 40px rgba(229, 9, 20, 0.4),
                0 0 30px rgba(255, 59, 59, 0.3);
        }

        .related-img-wrapper {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid rgba(229, 9, 20, 0.3);
        }

        .related-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .related-card:hover .related-img {
            transform: scale(1.1) rotate(2deg);
        }

        .discount-badge-small {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(229, 9, 20, 0.5);
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .old-price {
            text-decoration: line-through;
            color: #888;
        }

        .new-price {
            color: var(--accent);
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* Swiper Navigation */
        .swiper-button-next,
        .swiper-button-prev {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: #fff !important;
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.4);
            transition: all 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(229, 9, 20, 0.6);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        /* Animations */
        .animate-in {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.7s ease;
        }

        .js .animate-in {
            opacity: 0;
            transform: translateY(30px);
        }

        .js .animate-in.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-in.delay-1 {
            transition-delay: 0.2s;
        }

        .animate-in.delay-2 {
            transition-delay: 0.4s;
        }

        .animate-in.delay-3 {
            transition-delay: 0.6s;
        }

        /* Strength Bar Animation */
        @keyframes strengthBar {
            0%, 100% { width: 0%; }
            50% { width: 100%; }
        }

        /* Gym Equipment Icon Animation */
        .gym-icon {
            display: inline-block;
            animation: liftWeight 2s ease-in-out infinite;
        }

        @keyframes liftWeight {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-5px) rotate(-5deg); }
            75% { transform: translateY(-5px) rotate(5deg); }
        }

        /* Muscle Flex Effect */
        @keyframes muscleFlex {
            0%, 100% { transform: scaleX(1); }
            50% { transform: scaleX(1.05); }
        }

        /* Power Pulse Effect */
        .power-pulse {
            position: relative;
        }

        .power-pulse::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            border: 2px solid var(--accent);
            border-radius: inherit;
            transform: translate(-50%, -50%);
            animation: powerPulse 2s infinite;
            opacity: 0;
        }

        @keyframes powerPulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }

            .discounted-price,
            .final-price {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>

@endsection
