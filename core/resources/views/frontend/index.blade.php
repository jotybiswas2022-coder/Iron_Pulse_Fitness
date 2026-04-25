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
@endphp

<!-- ================= HERO SLIDER ================= -->
<section class="hero-slider">

    @if($slider && ($slider->slider1 || $slider->slider2))

        @if($slider->slider1)
        <div class="slide active">
            <img src="{{ config('app.storage_url') }}{{ $slider->slider1 }}" alt="">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <span class="badge-tag">
                    <i class="bi bi-stars"></i> Premium Store
                </span>
                <h1>Welcome to <span>Phone Store</span></h1>
                <p>Quality products at the best price in Bangladesh.</p>
                <div class="slide-btns">
                    <a href="#products" class="btn-primary-custom">
                        Shop Now <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($slider->slider2)
        <div class="slide {{ !$slider->slider1 ? 'active' : '' }}">
            <img src="{{ config('app.storage_url') }}{{ $slider->slider2 }}" alt="">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <span class="badge-tag">
                    <i class="bi bi-lightning"></i> New Arrival
                </span>
                <h1>Latest <span>Collection</span></h1>
                <p>Discover the newest smartphones and gadgets.</p>
                <div class="slide-btns">
                    <a href="#products" class="btn-primary-custom">
                        Explore <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

    @endif

</section>

<!-- ================= PRODUCTS ================= -->
<section id="products" class="products-section">
    <div class="products-container">

        <div class="section-header fade-in-up visible">
            <h2>Latest Products</h2>
            <p>Browse all available products</p>
        </div>

        <div class="products-grid">

            @forelse(($products ?? collect())->sortByDesc('created_at') as $product)
            <div class="product-card fade-in-up visible">

                @if($product->discount > 0)
                    <span class="product-badge">
                        {{ $product->discount }}% OFF
                    </span>
                @endif

                <div class="product-img-wrap">
                    <a href="{{ url('/product/'.$product->id) }}">
                        <img src="{{ $product->image ? config('app.storage_url').$product->image : asset('frontend/img/no-image.png') }}" alt="">
                    </a>
                </div>

                <div class="product-info">
                    <div class="product-name">
                        {{ $product->name }}
                    </div>

                    <div class="product-price-row">
                        <div class="product-price">
                            @if($product->discount > 0)
                                <span class="old">
                                    {{ $currency }} {{ number_format($product->price,2) }}
                                </span><br>
                                <span class="current">
                                    {{ $currency }} {{ number_format($product->price - ($product->price*$product->discount/100),2) }}
                                </span>
                            @else
                                <span class="current">
                                    {{ $currency }} {{ number_format($product->price,2) }}
                                </span>
                            @endif
                        </div>

                        @if(function_exists('IsAddedToCart') && IsAddedToCart(auth()->id(), $product->id))
                            <a href="{{ url('/product/'.$product->id) }}" class="product-buy in-cart">
                                <i class="bi bi-check-lg"></i>
                            </a>
                        @else
                            <a href="{{ url('/product/'.$product->id) }}" class="product-buy">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-white py-5 w-100">
                <h5>No Products Available</h5>
            </div>
            @endforelse

        </div>
    </div>
</section>

<!-- ================= CONTACT ================= -->
<section id="contactSection" class="contact-section">
    <div class="contact-container">

        <div class="section-header fade-in-up visible">
            <h2>Get In Touch</h2>
            <p>We would love to hear from you</p>
        </div>

        <div class="contact-grid">

            <div class="contact-form-card fade-in-up visible">
                <form action="{{ url('/contactus') }}" method="POST" id="contactForm">
                    @csrf

                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-input" required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="submit-btn">
                        Send Message
                    </button>
                </form>
            </div>

            <div class="contact-info-card fade-in-up visible">

                <div class="info-box">
                    <div class="info-box-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h6>Address</h6>
                    <p>Moylapota, Khulna, Bangladesh</p>
                </div>

                <div class="info-box">
                    <div class="info-box-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h6>Phone</h6>
                    <p>+880 1234 567 890</p>
                </div>

                <div class="info-box">
                    <div class="info-box-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h6>Email</h6>
                    <p>phonestore@gmail.com</p>
                </div>

            </div>

        </div>
    </div>
</section>

<script>
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

    // first show
    showSlide(current);

    // auto change every 4 seconds
    setInterval(nextSlide, 4000);
});

    document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {

        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending...';
    });
});
</script>


<style>
        :root {
            --primary: #0f172a;
            --primary-light: #1e293b;
            --primary-darker: #020617;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-glow: rgba(59, 130, 246, 0.35);
            --text-white: #f1f5f9;
            --text-light: #cbd5e1;
            --text-muted: #94a3b8;
            --card-border: rgba(59, 130, 246, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary);
            color: var(--text-white);
            overflow-x: hidden;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--primary-darker); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 10px; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--card-border);
            padding: 14px 0;
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            background: rgba(2, 6, 23, 0.95);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .nav-container {
            max-width: 1280px; margin: 0 auto; padding: 0 24px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .nav-logo {
            font-size: 1.5rem; font-weight: 800; color: var(--text-white);
            text-decoration: none; display: flex; align-items: center; gap: 10px;
        }
        .nav-logo .logo-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        .nav-links { display: flex; gap: 8px; align-items: center; }
        .nav-links a {
            color: var(--text-light); text-decoration: none; padding: 8px 16px;
            border-radius: 10px; font-weight: 500; font-size: 0.9rem;
            transition: all 0.3s ease; position: relative;
        }
        .nav-links a:hover { color: #fff; background: rgba(59, 130, 246, 0.15); }
        .nav-links a.active { color: var(--accent); background: rgba(59, 130, 246, 0.1); }
        .nav-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff !important; padding: 10px 22px !important;
            border-radius: 12px !important; font-weight: 600 !important;
            box-shadow: 0 4px 20px var(--accent-glow);
            transition: all 0.3s ease !important;
        }
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5) !important;
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        }
        .mobile-toggle {
            display: none; background: none; border: none; color: var(--text-white);
            font-size: 1.5rem; cursor: pointer; padding: 8px;
            border-radius: 10px; transition: 0.3s;
        }
        .mobile-toggle:hover { background: rgba(59, 130, 246, 0.15); }
        .mobile-menu {
            display: none; position: fixed; top: 70px; left: 0; right: 0;
            background: rgba(2, 6, 23, 0.98); backdrop-filter: blur(20px);
            padding: 20px; border-bottom: 1px solid var(--card-border);
            flex-direction: column; gap: 8px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: var(--text-light); text-decoration: none; padding: 14px 20px;
            border-radius: 12px; font-weight: 500; transition: 0.3s;
        }
        .mobile-menu a:hover { background: rgba(59, 130, 246, 0.15); color: #fff; }

        /* ===== HERO SLIDER ===== */
        .hero-slider {
            position: relative; width: 100%; height: 600px;
            overflow: hidden; margin-top: 68px;
        }
        .slide {
            position: absolute; inset: 0; opacity: 0;
            transition: opacity 1s ease-in-out, transform 1s ease;
            transform: scale(1.05);
        }
        .slide.active { opacity: 1; transform: scale(1); }
        .slide img {
            width: 100%; height: 100%; object-fit: cover;
            filter: brightness(0.4);
        }
        .slide-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.4) 50%, rgba(59,130,246,0.1) 100%);
        }
        .slide-content {
            position: absolute; inset: 0; display: flex; flex-direction: column;
            justify-content: center; padding: 0 10%;
            z-index: 2;
        }
        .slide-content .badge-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 6px 16px; border-radius: 50px; font-size: 0.8rem;
            color: #93c5fd; font-weight: 600; margin-bottom: 20px; width: fit-content;
            backdrop-filter: blur(10px);
        }
        .slide-content h1 {
            font-size: 3.5rem; font-weight: 900; color: #fff;
            line-height: 1.1; margin-bottom: 16px; max-width: 600px;
        }
        .slide-content h1 span { color: var(--accent); }
        .slide-content p {
            font-size: 1.15rem; color: var(--text-light); max-width: 500px;
            margin-bottom: 30px; line-height: 1.7;
        }
        .slide-btns { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; padding: 14px 32px; border-radius: 14px;
            font-weight: 700; font-size: 1rem; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 10px 30px var(--accent-glow);
            transition: all 0.3s ease; text-decoration: none;
        }
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
        }
        .btn-outline-custom {
            background: transparent; color: #fff; padding: 14px 32px;
            border-radius: 14px; font-weight: 600; font-size: 1rem;
            border: 2px solid rgba(255,255,255,0.2); cursor: pointer;
            display: inline-flex; align-items: center; gap: 10px;
            transition: all 0.3s ease; text-decoration: none;
            backdrop-filter: blur(10px);
        }
        .btn-outline-custom:hover {
            border-color: var(--accent); color: var(--accent);
            background: rgba(59, 130, 246, 0.1); transform: translateY(-3px);
        }
        .slider-dots {
            position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 10px; z-index: 10;
        }
        .slider-dot {
            width: 12px; height: 12px; border-radius: 50%;
            background: rgba(255,255,255,0.3); cursor: pointer;
            transition: all 0.3s ease; border: 2px solid transparent;
        }
        .slider-dot.active {
            background: var(--accent); width: 36px; border-radius: 10px;
            box-shadow: 0 0 15px var(--accent-glow);
        }
        .slider-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 50px; height: 50px; border-radius: 14px;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px);
            border: 1px solid var(--card-border); color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.2rem; transition: 0.3s; z-index: 10;
        }
        .slider-arrow:hover {
            background: var(--accent); box-shadow: 0 8px 25px var(--accent-glow);
        }
        .slider-arrow.prev { left: 24px; }
        .slider-arrow.next { right: 24px; }

        /* ===== FEATURES BAR ===== */
        .features-bar {
            background: var(--primary-light);
            border-top: 1px solid var(--card-border);
            border-bottom: 1px solid var(--card-border);
            padding: 30px 0;
        }
        .features-grid {
            max-width: 1280px; margin: 0 auto; padding: 0 24px;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
        }
        .feature-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 20px; border-radius: 14px;
            transition: 0.3s;
        }
        .feature-item:hover { background: rgba(59, 130, 246, 0.08); }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(59, 130, 246, 0.12);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent); font-size: 1.3rem; flex-shrink: 0;
        }
        .feature-item h6 { font-weight: 700; font-size: 0.85rem; color: var(--text-white); margin-bottom: 2px; }
        .feature-item p { font-size: 0.75rem; color: var(--text-muted); }

        /* ===== SECTION TITLE ===== */
        .section-header { text-align: center; margin-bottom: 50px; position: relative; }
        .section-header .badge-label {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 6px 18px; border-radius: 50px; font-size: 0.8rem;
            color: #93c5fd; font-weight: 600; margin-bottom: 14px;
        }
        .section-header h2 {
            font-size: 2.5rem; font-weight: 800; color: var(--text-white);
            position: relative; display: inline-block;
        }
        .section-header h2::after {
            content: ""; position: absolute; bottom: -12px; left: 50%;
            transform: translateX(-50%); width: 60px; height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 4px;
        }
        .section-header p { color: var(--text-muted); font-size: 1.05rem; margin-top: 24px; }

        /* ===== PRODUCTS ===== */
        .products-section {
            background: var(--primary); padding: 80px 0; position: relative;
        }
        .products-container { max-width: 1280px; margin: 0 auto; padding: 0 24px; position: relative; }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .product-card {
            background: var(--primary-light);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 40px rgba(59,130,246,0.08);
            border-color: rgba(59, 130, 246, 0.35);
        }
        .product-badge {
            position: absolute; top: 14px; left: 14px; z-index: 5;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; padding: 5px 14px; border-radius: 10px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        .product-badge.new {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        .product-wishlist {
            position: absolute; top: 14px; right: 14px; z-index: 5;
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); cursor: pointer; transition: 0.3s;
            font-size: 1rem;
        }
        .product-wishlist:hover, .product-wishlist.liked {
            background: rgba(239, 68, 68, 0.2); color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }
        .product-img-wrap {
            position: relative; overflow: hidden; height: 230px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(180deg, rgba(30,41,59,0.5), rgba(15,23,42,0.3));
            padding: 20px;
        }
        .product-img-wrap img {
            max-height: 180px; max-width: 100%; object-fit: contain;
            transition: transform 0.5s ease;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
        }
        .product-card:hover .product-img-wrap img { transform: scale(1.1) rotate(2deg); }
        .product-info { padding: 20px; }
        .product-category {
            font-size: 0.72rem; font-weight: 600; color: var(--accent);
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }
        .product-name {
            font-size: 0.95rem; font-weight: 700; color: var(--text-white);
            line-height: 1.4; margin-bottom: 10px; min-height: 42px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-rating { display: flex; align-items: center; gap: 4px; margin-bottom: 12px; }
        .product-rating i { color: #f59e0b; font-size: 0.8rem; }
        .product-rating span { color: var(--text-muted); font-size: 0.75rem; margin-left: 4px; }
        .product-price-row {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 14px; border-top: 1px solid var(--card-border);
        }
        .product-price .old {
            text-decoration: line-through; color: var(--text-muted);
            font-size: 0.82rem; margin-right: 8px;
        }
        .product-price .current {
            color: var(--accent); font-size: 1.2rem; font-weight: 800;
        }
        .product-buy {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; transition: 0.3s;
            box-shadow: 0 4px 15px var(--accent-glow);
        }
        .product-buy:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.5);
        }
        .product-buy.in-cart {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        /* ===== PARTICLES ===== */
        .particle {
            position: absolute; border-radius: 50%;
            background: rgba(59, 130, 246, 0.08);
            pointer-events: none; animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* ===== CONTACT ===== */
        .contact-section {
            background: linear-gradient(180deg, var(--primary-darker) 0%, var(--primary) 100%);
            padding: 80px 0; position: relative; overflow: hidden;
        }
        .contact-container { max-width: 1280px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 2; }
        .contact-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            align-items: start;
        }
        .contact-form-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px; padding: 40px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-weight: 600; font-size: 0.85rem;
            color: var(--text-light); margin-bottom: 8px;
        }
        .form-group label i { color: var(--accent); margin-right: 6px; }
        .form-input {
            width: 100%; padding: 14px 18px; border-radius: 12px;
            background: var(--primary); border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--text-white); font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: all 0.3s; outline: none;
        }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background: rgba(15, 23, 42, 0.9);
        }
        .form-input::placeholder { color: var(--text-muted); }
        textarea.form-input { resize: vertical; min-height: 130px; }
        .submit-btn {
            width: 100%; padding: 16px; border: none; border-radius: 14px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 10px 30px var(--accent-glow); transition: all 0.3s;
        }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
        }
        .contact-info-card {
            display: flex; flex-direction: column; gap: 20px;
        }
        .info-box {
            background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(20px);
            border: 1px solid var(--card-border); border-radius: 18px; padding: 28px;
            transition: 0.3s;
        }
        .info-box:hover {
            border-color: rgba(59, 130, 246, 0.35);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .info-box-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: rgba(59, 130, 246, 0.12);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent); font-size: 1.3rem; margin-bottom: 14px;
        }
        .info-box h6 { font-weight: 700; font-size: 0.9rem; color: var(--text-white); margin-bottom: 6px; }
        .info-box p { color: var(--text-muted); font-size: 0.85rem; line-height: 1.6; }
        .social-links { display: flex; gap: 10px; margin-top: 10px; }
        .social-link {
            width: 44px; height: 44px; border-radius: 12px;
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid var(--card-border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-light); font-size: 1.15rem;
            transition: 0.3s; cursor: pointer; text-decoration: none;
        }
        .social-link:hover {
            background: var(--accent); color: #fff;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px var(--accent-glow);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--primary-darker);
            border-top: 1px solid var(--card-border);
            padding: 40px 0 20px;
        }
        .footer-container {
            max-width: 1280px; margin: 0 auto; padding: 0 24px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px;
            padding-bottom: 30px; border-bottom: 1px solid var(--card-border);
        }
        .footer-about h4 { font-weight: 800; font-size: 1.3rem; margin-bottom: 14px; display: flex; align-items: center; gap: 10px; }
        .footer-about p { color: var(--text-muted); font-size: 0.85rem; line-height: 1.7; }
        .footer-col h5 { font-weight: 700; font-size: 0.9rem; margin-bottom: 16px; color: var(--text-white); }
        .footer-col a {
            display: block; color: var(--text-muted); font-size: 0.85rem;
            text-decoration: none; padding: 5px 0; transition: 0.3s;
        }
        .footer-col a:hover { color: var(--accent); padding-left: 6px; }
        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 20px; flex-wrap: wrap; gap: 10px;
        }
        .footer-bottom p { color: var(--text-muted); font-size: 0.8rem; }

        /* ===== ANIMATIONS ===== */
        .fade-in-up {
            opacity: 0; transform: translateY(40px);
            transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .fade-in-up.visible { opacity: 1; transform: translateY(0); }

        /* ===== ALERT ===== */
        .alert-success-custom {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; padding: 14px 24px; border-radius: 14px;
            text-align: center; font-weight: 600; margin: 80px 20px 0;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            animation: slideDown 0.5s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== TOAST ===== */
        .toast-notification {
            position: fixed; bottom: 30px; right: 30px; z-index: 9999;
            background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(20px);
            border: 1px solid var(--card-border); border-radius: 16px;
            padding: 16px 24px; display: flex; align-items: center; gap: 12px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            transform: translateX(120%); transition: transform 0.4s ease;
            color: var(--text-white); font-weight: 600; font-size: 0.9rem;
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-notification .toast-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(16, 185, 129, 0.15);
            display: flex; align-items: center; justify-content: center;
            color: #10b981; font-size: 1.1rem;
        }

        /* ===== BACK TO TOP ===== */
        .back-to-top {
            position: fixed; bottom: 30px; left: 30px; z-index: 999;
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; box-shadow: 0 8px 25px var(--accent-glow);
            opacity: 0; transform: translateY(20px); transition: all 0.3s;
        }
        .back-to-top.visible { opacity: 1; transform: translateY(0); }
        .back-to-top:hover { transform: translateY(-4px); }

        /* ===== GLOW ORB ===== */
        .glow-orb {
            position: absolute; border-radius: 50%; pointer-events: none;
            filter: blur(100px); opacity: 0.15;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-toggle { display: block; }
            .hero-slider { height: 500px; }
            .slide-content h1 { font-size: 2rem; }
            .slide-content p { font-size: 0.95rem; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .contact-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 24px; }
            .section-header h2 { font-size: 1.8rem; }
            .product-img-wrap { height: 180px; }
            .slider-arrow { display: none; }
        }
        @media (max-width: 480px) {
            .hero-slider { height: 420px; }
            .slide-content { padding: 0 6%; }
            .slide-content h1 { font-size: 1.6rem; }
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .product-info { padding: 14px; }
            .product-img-wrap { height: 150px; padding: 14px; }
            .product-price .current { font-size: 1rem; }
            .contact-form-card { padding: 24px; }
        }

        /* ===== RESPONSIVE ===== */

@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .contact-grid {
        grid-template-columns: 1fr;
    }

    .slide-content h1 {
        font-size: 2.4rem;
    }
}

@media (max-width: 576px) {
    .hero-slider {
        height: 420px;
    }

    .slide-content {
        padding: 0 20px;
    }

    .slide-content h1 {
        font-size: 1.8rem;
    }

    .slide-content p {
        font-size: 0.9rem;
    }

    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
}
    </style>

@endsection 