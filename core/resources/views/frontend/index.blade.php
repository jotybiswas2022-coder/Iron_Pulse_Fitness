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
            <h2>Latest Packs</h2>
            <p>Browse type wise packs</p>
        </div>

        {{-- LOOP TYPE --}}
        @forelse($groupedPacks as $type => $packsByType)

            <div class="category-section mb-5">

                <h3 class="category-title mb-3">
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
                                {{ $pack->name }}
                            </div>

                            <div class="product-price-row">
                                <div class="product-price">

                                    @if($pack->discount > 0)
                                        <span class="old">
                                            {{ $currency }} {{ number_format($pack->pack_price,2) }}
                                        </span><br>
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
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                @else
                                    <a href="{{ url('/pack/'.$pack->id) }}" class="product-buy">
                                        <i class="bi bi-cart-plus"></i>
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
                <h5>No packs Available</h5>
            </div>
        @endforelse

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

@endsection 
