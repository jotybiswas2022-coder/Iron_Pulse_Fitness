@extends('frontend.app')

@section('content')

@if(session('success'))
<div class="alert-success-custom">
    {{ session('success') }}
</div>
@endif

@php
use App\Models\Setting;
use App\Models\Slider;

$settings = Setting::first();
$currency = $settings?->currency ?? '৳';
$slider = Slider::latest()->first();

// packs grouped by type (basic, standard, premium)
$groupedPacks = ($packs ?? collect())
    ->sortByDesc('created_at')
    ->groupBy(function ($pack) {
        return strtolower((string) ($pack->type ?? 'other'));
    });

$typeLabels = [
    'basic' => 'Basic Packs',
    'standard' => 'Standard Packs',
    'premium' => 'Premium Packs',
    'other' => 'Other Packs',
];

$groupedPacks = collect(['basic', 'standard', 'premium', 'other'])
    ->mapWithKeys(fn ($type) => [$type => $groupedPacks->get($type, collect())])
    ->filter(fn ($packsByType) => $packsByType->isNotEmpty());
@endphp


<!-- ================= HERO SLIDER ================= -->
<section class="hero-slider">

    @if($slider && ($slider->slider1 || $slider->slider2))

        @if($slider->slider1)
        <div class="slide active">
            <img src="{{ config('app.storage_url') }}{{ $slider->slider1 }}">
        </div>
        @endif

        @if($slider->slider2)
        <div class="slide {{ !$slider->slider1 ? 'active' : '' }}">
            <img src="{{ config('app.storage_url') }}{{ $slider->slider2 }}">
        </div>
        @endif

    @endif

</section>


<!-- ================= PACKS ================= -->
<section id="products" class="products-section">
    <div class="products-container">

        <div class="section-header fade-in-up visible">
            <h2><i class="bi bi-lightning-charge-fill"></i> Iron Pulse Packs</h2>
            <p>Train Hard. Stay Strong. Upgrade Your Limits.</p>
        </div>

        {{-- LOOP TYPE --}}
        @forelse($groupedPacks as $type => $packsByType)

        <div class="category-section mb-5">

            <h3 class="category-title mb-3">
                <i class="bi bi-dumbbell"></i>
                {{ $typeLabels[$type] ?? ucfirst($type) . ' Packs' }}
            </h3>

            <div class="products-grid">

                {{-- LOOP PACKS --}}
                @foreach($packsByType as $pack)
                <div class="product-card">

                    @if($pack->discount > 0)
                    <span class="product-badge">
                        {{ $pack->discount }}% OFF
                    </span>
                    @endif

                    <div class="product-img-wrap">
                        <a href="{{ url('/pack/'.$pack->id) }}">
                            <img src="{{ $pack->image ? config('app.storage_url').$pack->image : asset('frontend/img/no-image.png') }}">
                        </a>
                    </div>

                    <div class="product-info">

                        <div class="product-name">
                            <i class="bi bi-fire"></i> {{ $pack->name }}
                        </div>

                        <div class="product-price-row">

                            <div class="product-price">

                                @if($pack->discount > 0)
                                    <span class="old">
                                        {{ $currency }} {{ number_format($pack->pack_price,2) }}
                                    </span>
                                    <span class="current">
                                        {{ $currency }} {{ number_format($pack->pack_price - ($pack->pack_price*$pack->discount/100),2) }}
                                    </span>
                                @else
                                    <span class="current">
                                        {{ $currency }} {{ number_format($pack->pack_price,2) }}
                                    </span>
                                @endif

                            </div>

                            @if(function_exists('IsAddedToCart') && IsAddedToCart(auth()->id(), $pack->id))
                                <a href="{{ url('/pack/'.$pack->id) }}" class="product-buy in-cart">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            @else
                                <a href="{{ url('/pack/'.$pack->id) }}" class="product-buy">
                                    <i class="bi bi-cart-plus-fill"></i>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        @empty
        <div class="text-center text-white py-5 w-100">
            <h5><i class="bi bi-exclamation-triangle"></i> No Packs Available</h5>
        </div>
        @endforelse

    </div>
</section>


<!-- ================= CONTACT ================= -->
<section id="contactSection" class="contact-section">
    <div class="contact-container">

        <div class="section-header fade-in-up visible">
            <h2><i class="bi bi-person-lines-fill"></i> Get In Touch</h2>
            <p>We would love to hear from you</p>
        </div>

        <div class="contact-grid">

            <div class="contact-form-card fade-in-up visible">

                <form action="{{ url('/contactus') }}" method="POST" id="contactForm">
                    @csrf

                    <div class="form-group">
                        <label><i class="bi bi-person-fill"></i> Your Name</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label><i class="bi bi-envelope-fill"></i> Email</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label><i class="bi bi-chat-dots-fill"></i> Message</label>
                        <textarea name="message" class="form-input" required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="submit-btn">
                        <i class="bi bi-send-fill"></i> Send Message
                    </button>

                </form>

            </div>

            <div class="contact-info-card fade-in-up visible">

                <div class="info-box">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h6>Address</h6>
                    <p>Moylapota, Khulna, Bangladesh</p>
                </div>

                <div class="info-box">
                    <i class="bi bi-telephone-fill"></i>
                    <h6>Phone</h6>
                    <p>+880 1234 567 890</p>
                </div>

                <div class="info-box">
                    <i class="bi bi-envelope-fill"></i>
                    <h6>Email</h6>
                    <p>ironpulsegym@gmail.com</p>
                </div>

            </div>

        </div>
    </div>
</section>


<script>
        // ================= HERO SLIDER =================
        document.addEventListener("DOMContentLoaded", function () {
            const slides = document.querySelectorAll(".hero-slider .slide");

            if (!slides.length) return;

            let current = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle("active", i === index);
                });
            }

            function nextSlide() {
                current = (current + 1) % slides.length;
                showSlide(current);
            }

            showSlide(current);
            setInterval(nextSlide, 4000);
        });

        // ================= CONTACT FORM =================
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';

                // Simulate sending
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sent!';
                    
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-send-fill"></i> Send Message';
                        form.reset();
                    }, 2000);
                }, 1500);
            });
        });

        // ================= SCROLL ANIMATIONS =================
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.product-card, .info-box').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ================= ALERT ================= */
        .alert-success-custom {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: var(--text);
            padding: 1rem 2rem;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ================= HERO SLIDER ================= */
        .hero-slider {
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
            background: var(--primary);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background: linear-gradient(135deg, rgba(15,15,15,0.8), rgba(229,9,20,0.6)), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%230F0F0F" width="1200" height="600"/><text x="50%" y="50%" font-size="120" fill="%23E50914" text-anchor="middle" dominant-baseline="middle" font-weight="bold" opacity="0.1">IRON PULSE</text></svg>');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide.active {
            opacity: 1;
            animation: zoomPulse 8s ease-in-out infinite;
        }

        @keyframes zoomPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6);
        }

        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(15,15,15,0.7) 0%, rgba(229,9,20,0.5) 100%);
            z-index: 1;
        }

        .slide::after {
            content: 'IRON PULSE GYM';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 5rem;
            font-weight: 900;
            color: var(--text);
            text-shadow: 0 0 30px var(--secondary);
            z-index: 2;
            letter-spacing: 8px;
            animation: textPulse 2s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% { 
                text-shadow: 0 0 30px var(--secondary), 0 0 60px var(--accent);
                transform: translate(-50%, -50%) scale(1);
            }
            50% { 
                text-shadow: 0 0 50px var(--secondary), 0 0 100px var(--accent);
                transform: translate(-50%, -50%) scale(1.02);
            }
        }

        /* ================= PRODUCTS SECTION ================= */
        .products-section {
            padding: 80px 20px;
            background: linear-gradient(180deg, var(--background) 0%, var(--primary) 100%);
            position: relative;
        }

        .products-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--secondary), var(--accent), transparent);
            animation: lineGlow 3s ease-in-out infinite;
        }

        @keyframes lineGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .products-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            animation: headerPulse 2s ease-in-out infinite;
        }

        @keyframes headerPulse {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.2); }
        }

        .section-header p {
            font-size: 1.2rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            border-radius: 2px;
            animation: barExpand 2s ease-in-out infinite;
        }

        @keyframes barExpand {
            0%, 100% { width: 100px; }
            50% { width: 150px; }
        }

        .category-section {
            margin-bottom: 80px;
        }

        .category-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 30px;
            padding-left: 20px;
            border-left: 5px solid var(--secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            animation: slideInLeft 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .product-card {
            background: var(--primary);
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            animation: cardFadeIn 0.6s ease-out;
        }

        @keyframes cardFadeIn {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--secondary);
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.4);
        }

        .product-card:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(229,9,20,0.1), rgba(255,59,59,0.1));
            z-index: 1;
            pointer-events: none;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: var(--text);
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.85rem;
            z-index: 3;
            animation: badgePulse 2s ease-in-out infinite;
            box-shadow: 0 4px 15px rgba(229,9,20,0.5);
        }

        @keyframes badgePulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(229,9,20,0.5);
            }
            50% { 
                transform: scale(1.05);
                box-shadow: 0 6px 25px rgba(229,9,20,0.7);
            }
        }

        .product-img-wrap {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: var(--background);
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.15) rotate(2deg);
        }

        .product-info {
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price .old {
            color: #666;
            text-decoration: line-through;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 5px;
        }

        .product-price .current {
            color: var(--accent);
            font-size: 1.5rem;
            font-weight: 800;
            display: block;
            text-shadow: 0 0 10px rgba(255,59,59,0.3);
        }

        .product-buy {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: var(--text);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(229,9,20,0.4);
        }

        .product-buy:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 6px 25px rgba(229,9,20,0.6);
        }

        .product-buy.in-cart {
            background: linear-gradient(135deg, #00c853, #00e676);
            box-shadow: 0 4px 15px rgba(0,200,83,0.4);
        }

        /* ================= CONTACT SECTION ================= */
        .contact-section {
            padding: 80px 20px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--background) 100%);
            position: relative;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .contact-form-card,
        .contact-info-card {
            background: var(--primary);
            padding: 40px;
            border-radius: 15px;
            border: 2px solid rgba(229,9,20,0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .contact-form-card:hover,
        .contact-info-card:hover {
            border-color: var(--secondary);
            box-shadow: 0 10px 30px rgba(229,9,20,0.3);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            background: var(--background);
            border: 2px solid rgba(229,9,20,0.3);
            border-radius: 8px;
            color: var(--text);
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 20px rgba(229,9,20,0.3);
        }

        textarea.form-input {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            border: none;
            border-radius: 8px;
            color: var(--text);
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229,9,20,0.5);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .info-box {
            background: var(--background);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--secondary);
            transition: all 0.3s ease;
            position: relative;
        }

        .info-box:hover {
            transform: translateX(10px);
            border-left-width: 8px;
            box-shadow: -5px 0 15px rgba(229,9,20,0.3);
        }

        .info-box-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            animation: iconRotate 3s ease-in-out infinite;
        }

        @keyframes iconRotate {
            0%, 100% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(-10deg) scale(1.05); }
            75% { transform: rotate(10deg) scale(1.05); }
        }

        .info-box h6 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-box p {
            color: #ccc;
            font-size: 1rem;
            line-height: 1.6;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .slide::after {
                font-size: 2.5rem;
                letter-spacing: 3px;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .category-title {
                font-size: 1.5rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .hero-slider {
                height: 400px;
            }
        }

        @media (max-width: 480px) {
            .slide::after {
                font-size: 1.8rem;
                letter-spacing: 2px;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }
        }

        /* ================= STRENGTH BAR ANIMATION ================= */
        .strength-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
            z-index: 9999;
        }

        .strength-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            width: 0;
            animation: strengthGrow 3s ease-in-out infinite;
        }

        @keyframes strengthGrow {
            0%, 100% { width: 0; }
            50% { width: 100%; }
        }

        /* No Packs Message */
        .no-packs {
            text-align: center;
            padding: 60px 20px;
            width: 100%;
        }

        .no-packs h5 {
            font-size: 2rem;
            color: var(--text);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>

@endsection 
