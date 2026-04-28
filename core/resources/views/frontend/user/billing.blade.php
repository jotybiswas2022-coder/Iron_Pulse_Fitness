@extends('frontend.app')

@section('content')

<div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="container py-5 main-content">

    <h2 class="fw-bold text-center gradient-text mb-2">Checkout</h2>
    <p class="text-center section-subtitle mb-5">Complete your order securely</p>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-custom-success animate-in">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-custom-danger animate-in">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-custom-danger animate-in">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@php
use App\Models\Setting;
$settings   = Setting::first();
$delivery   = $settings?->delivery_charge ?? 0;
$taxPercent = $settings?->tax_percentage ?? 0;
$currency   = $settings?->currency ?? '৳';
$subtotal   = 0;
$user       = auth()->user();
@endphp

<form action="/user/order/store" method="post">
@csrf

@if($carts->count() == 0)

    {{-- Empty Cart --}}
    <div class="empty-cart">
        <div class="empty-cart-icon">
            <i class="bi bi-cart-x"></i>
        </div>
        <h4>Your cart is empty</h4>
        <a href="{{ url('/') }}" class="btn-continue">
            <i class="bi bi-arrow-left"></i> Continue Shopping
        </a>
    </div>

@else

{{-- Calculate --}}
@foreach($carts as $cart)
@php
$pack = $cart->pack;
$priceAfterDiscount = $pack->pack_price * (100 - ($pack->discount ?? 0)) / 100;
$subtotal += $priceAfterDiscount * $cart->quantity;
@endphp
@endforeach

@php
$taxAmount  = ($subtotal * $taxPercent) / 100;
$grandTotal = $subtotal + $taxAmount + $delivery;
@endphp

<div class="row g-4">

    {{-- Billing --}}
    <div class="col-lg-7 animate-in animate-delay-1">
        <div class="dark-card billing-card">

            <h4><i class="bi bi-person-circle"></i> Billing Details</h4>

            <div class="row g-3">

                <div class="col-md-6">
                    <input type="text" name="firstname"
                        value="{{ old('firstname', $user->firstname ?? '') }}"
                        class="form-control-dark" placeholder="First Name" required>
                </div>

                <div class="col-md-6">
                    <input type="text" name="lastname"
                        value="{{ old('lastname', $user->lastname ?? '') }}"
                        class="form-control-dark" placeholder="Last Name" required>
                </div>

                <div class="col-md-6">
                    <input type="email" name="email"
                        value="{{ old('email', $user->email ?? '') }}"
                        class="form-control-dark" placeholder="Email" required>
                </div>

                <div class="col-md-6">
                    <input type="tel" name="phone"
                        value="{{ old('phone', $user->phone ?? '') }}"
                        class="form-control-dark" placeholder="Phone" required>
                </div>

                <div class="col-12">
                    <input type="text" name="address"
                        value="{{ old('address', $user->address ?? '') }}"
                        class="form-control-dark" placeholder="Address" required>
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-5 animate-in animate-delay-2">
        <div class="sticky-sidebar">

            {{-- Cart Items --}}
            <div class="dark-card mb-4">
                <div class="card-header-dark">
                    <i class="bi bi-cart"></i> Your Cart
                </div>

                @foreach($carts as $cart)
                @php
                    $pack = $cart->pack;
                    $priceAfterDiscount = $pack->pack_price * (100 - ($pack->discount ?? 0)) / 100;
                @endphp

                <div class="cart-item">
                    <img src="{{ config('app.storage_url') }}{{ $pack->image }}" class="cart-img">
                    <div class="flex-grow-1">
                        <div class="cart-item-name">{{ $pack->name }}</div>
                        <div class="cart-item-meta">
                            {{ $cart->quantity }} × {{ number_format($priceAfterDiscount,2) }} {{ $currency }}
                        </div>
                    </div>
                    <div class="cart-item-price">
                        {{ number_format($priceAfterDiscount * $cart->quantity,2) }} {{ $currency }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Payment --}}
            <div class="dark-card p-3 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-credit-card"></i> Select Payment Method
                </h6>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cod" required>
                    <i class="bi bi-truck"></i>
                    <div class="payment-label-text">
                        Cash on Delivery
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="bkash">
                    <i class="bi bi-phone"></i>
                    <div class="payment-label-text">
                        bKash
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="nagad">
                    <i class="bi bi-wallet2"></i>
                    <div class="payment-label-text">
                        Nagad
                    </div>
                </label>
            </div>

            {{-- Summary --}}
            <div class="dark-card p-3">

                <hr class="summary-divider">

                <div class="summary-total">
                    <span>Total</span>
                    <span>{{ number_format($grandTotal,2) }} {{ $currency }}</span>
                </div>

                <button type="submit" class="btn-checkout">
                    <i class="bi bi-shield-check"></i>
                    Place Order
                </button>

                <div class="security-badge">
                    <i class="bi bi-lock-fill"></i>
                    Secure 256-bit SSL encrypted checkout
                </div>
            </div>

        </div>
    </div>

</div>

@endif
</form>
</div>

{{-- JS --}}
<script>
document.querySelectorAll('.payment-option input').forEach(radio => {
    radio.addEventListener('change', function(){
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        this.closest('.payment-option').classList.add('selected');
    });
});
</script>

<style>
        :root {
            --deep-black: #0F0F0F;
            --power-red: #E50914;
            --accent-red: #FF3B3B;
            --bg-dark: #121212;
            --text-white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-dark);
            color: var(--text-white);
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background with Gym-themed orbs */
        .bg-orbs {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: var(--power-red);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: var(--accent-red);
            bottom: -100px;
            right: -100px;
            animation-delay: 2s;
        }

        .orb-3 {
            width: 300px;
            height: 300px;
            background: var(--power-red);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(30px, -30px) scale(1.1);
            }
            50% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            75% {
                transform: translate(20px, 30px) scale(1.05);
            }
        }

        /* Dumbbell Animation */
        @keyframes dumbbell-lift {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Pulse Animation for Gym Energy */
        @keyframes gym-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(229, 9, 20, 0.7);
            }
            50% {
                box-shadow: 0 0 0 20px rgba(229, 9, 20, 0);
            }
        }

        /* Heartbeat Animation */
        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            10%, 30% {
                transform: scale(1.05);
            }
            20%, 40% {
                transform: scale(1);
            }
        }

        .main-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
        }

        /* Gradient Text with Gym Energy */
        .gradient-text {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--power-red), var(--accent-red), var(--text-white));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: heartbeat 2s ease-in-out infinite;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 900;
        }

        .section-subtitle {
            color: #999;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Alert Styles */
        .alert-custom-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
            border: 2px solid #28a745;
            color: #28a745;
            border-radius: 12px;
            padding: 15px 20px;
            animation: slideInDown 0.5s ease;
        }

        .alert-custom-danger {
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.1), rgba(229, 9, 20, 0.05));
            border: 2px solid var(--power-red);
            color: var(--accent-red);
            border-radius: 12px;
            padding: 15px 20px;
            animation: slideInDown 0.5s ease;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Dark Cards with Gym Theme */
        .dark-card {
            background: linear-gradient(145deg, rgba(21, 21, 21, 0.9), rgba(15, 15, 15, 0.9));
            border: 1px solid rgba(229, 9, 20, 0.3);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .dark-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .dark-card:hover::before {
            left: 100%;
        }

        .dark-card:hover {
            border-color: var(--power-red);
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(229, 9, 20, 0.3);
        }

        .billing-card h4 {
            color: var(--accent-red);
            margin-bottom: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .billing-card h4 i {
            margin-right: 10px;
            animation: dumbbell-lift 2s ease-in-out infinite;
        }

        /* Form Controls */
        .form-label {
            color: #ccc;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .form-control-dark {
            background: rgba(30, 30, 30, 0.8);
            border: 2px solid rgba(229, 9, 20, 0.3);
            color: var(--text-white);
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control-dark:focus {
            outline: none;
            border-color: var(--power-red);
            background: rgba(30, 30, 30, 0.95);
            box-shadow: 0 0 0 4px rgba(229, 9, 20, 0.1);
        }

        /* Card Header */
        .card-header-dark {
            background: linear-gradient(135deg, var(--power-red), var(--accent-red));
            color: var(--text-white);
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            margin: -30px -30px 20px -30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-header-dark i {
            margin-right: 8px;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        /* Cart Items */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(30, 30, 30, 0.5);
            border-radius: 10px;
            margin-bottom: 12px;
            border: 1px solid rgba(229, 9, 20, 0.2);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: rgba(30, 30, 30, 0.8);
            border-color: var(--power-red);
            transform: translateX(5px);
        }

        .cart-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--power-red);
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--text-white);
            font-size: 1rem;
        }

        .cart-item-meta {
            color: #999;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .cart-item-price {
            color: var(--accent-red);
            font-weight: 700;
            font-size: 1.1rem;
            margin-left: auto;
        }

        /* Payment Options */
        .payment-option {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(30, 30, 30, 0.5);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-option:hover {
            background: rgba(30, 30, 30, 0.8);
            border-color: var(--power-red);
            transform: scale(1.02);
        }

        .payment-option.selected {
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.2), rgba(229, 9, 20, 0.1));
            border-color: var(--power-red);
            animation: gym-pulse 2s ease-in-out infinite;
        }

        .payment-option input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: var(--power-red);
        }

        .payment-option i {
            font-size: 1.8rem;
            color: var(--accent-red);
        }

        .payment-label-text {
            flex-grow: 1;
            font-weight: 600;
            color: var(--text-white);
        }

        .payment-label-sub {
            font-size: 0.85rem;
            color: #999;
            margin-top: 3px;
        }

        /* Summary Divider */
        .summary-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--power-red), transparent);
            margin: 20px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-red);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Checkout Button */
        .btn-checkout {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--power-red), var(--accent-red));
            color: var(--text-white);
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout::before {
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

        .btn-checkout:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-checkout:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.5);
        }

        .btn-checkout i {
            margin-right: 8px;
        }

        /* Security Badge */
        .security-badge {
            text-align: center;
            margin-top: 15px;
            color: #999;
            font-size: 0.85rem;
            padding: 10px;
            background: rgba(30, 30, 30, 0.5);
            border-radius: 8px;
            border: 1px solid rgba(229, 9, 20, 0.2);
        }

        .security-badge i {
            color: var(--power-red);
            margin-right: 5px;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-cart-icon {
            font-size: 6rem;
            color: var(--power-red);
            margin-bottom: 20px;
            animation: dumbbell-lift 3s ease-in-out infinite;
        }

        .empty-cart h4 {
            color: var(--text-white);
            margin-bottom: 30px;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .btn-continue {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, var(--power-red), var(--accent-red));
            color: var(--text-white);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.5);
            color: var(--text-white);
        }

        .btn-continue i {
            margin-right: 8px;
        }

        /* Sticky Sidebar */
        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }

        /* Animation Classes */
        .animate-in {
            animation: fadeInUp 0.6s ease;
        }

        .animate-delay-1 {
            animation: fadeInUp 0.8s ease;
        }

        .animate-delay-2 {
            animation: fadeInUp 1s ease;
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

        /* Responsive */
        @media (max-width: 992px) {
            .gradient-text {
                font-size: 2rem;
            }

            .sticky-sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 576px) {
            .gradient-text {
                font-size: 1.5rem;
            }

            .dark-card {
                padding: 20px;
            }

            .cart-img {
                width: 60px;
                height: 60px;
            }
        }
    </style>

@endsection