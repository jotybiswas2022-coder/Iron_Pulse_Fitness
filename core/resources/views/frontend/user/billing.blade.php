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

    <!-- Alerts -->
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

@foreach($carts as $cart)
@php
$product = $cart->product;
$priceAfterDiscount = $product->price * (100 - ($product->discount ?? 0)) / 100;
$subtotal += $priceAfterDiscount * $cart->quantity;
@endphp
@endforeach

@php
$taxAmount  = ($subtotal * $taxPercent) / 100;
$grandTotal = $subtotal + $taxAmount + $delivery;
@endphp

<div class="row g-4">

    <!-- Billing -->
    <div class="col-lg-7 animate-in animate-delay-1">
        <div class="dark-card billing-card">

            <h4><i class="bi bi-person-circle"></i> Billing Details</h4>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname"
                        value="{{ old('firstname', $user->firstname ?? '') }}"
                        class="form-control-dark" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname"
                        value="{{ old('lastname', $user->lastname ?? '') }}"
                        class="form-control-dark" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email ?? '') }}"
                        class="form-control-dark" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone"
                        value="{{ old('phone', $user->phone ?? '') }}"
                        class="form-control-dark" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address"
                        value="{{ old('address', $user->address ?? '') }}"
                        class="form-control-dark" required>
                </div>

            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-5 animate-in animate-delay-2">
        <div class="sticky-sidebar">

            <!-- Cart Items -->
            <div class="dark-card mb-4">
                <div class="card-header-dark">
                    <i class="bi bi-cart"></i> Your Cart
                </div>

                @foreach($carts as $cart)
                @php
                    $product = $cart->product;
                    $priceAfterDiscount = $product->price * (100 - ($product->discount ?? 0)) / 100;
                @endphp

                <div class="cart-item">
                    <img src="{{ config('app.storage_url') }}{{ $product->image }}" class="cart-img">
                    <div class="flex-grow-1">
                        <div class="cart-item-name">{{ $product->name }}</div>
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

            <!-- Payment -->
            <div class="dark-card p-3 mb-4">
                <h6 class="fw-bold mb-3">Select Payment Method</h6>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cod" required>
                    <i class="bi bi-truck"></i>
                    <div class="payment-label-text">
                        Cash on Delivery
                        <div class="payment-label-sub">Pay when product arrives</div>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="bkash">
                    <i class="bi bi-phone"></i>
                    <div class="payment-label-text">
                        bKash
                        <div class="payment-label-sub">Instant mobile payment</div>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="nagad">
                    <i class="bi bi-wallet2"></i>
                    <div class="payment-label-text">
                        Nagad
                        <div class="payment-label-sub">Secure online payment</div>
                    </div>
                </label>
            </div>

            <!-- Summary -->
            <div class="dark-card p-4">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>{{ number_format($subtotal,2) }} {{ $currency }}</span>
                </div>

                <div class="summary-row">
                    <span>Tax ({{ $taxPercent }}%)</span>
                    <span>{{ number_format($taxAmount,2) }} {{ $currency }}</span>
                </div>

                <div class="summary-row">
                    <span>Delivery</span>
                    <span>{{ number_format($delivery,2) }} {{ $currency }}</span>
                </div>

                <hr class="summary-divider">

                <div class="summary-total">
                    <span>Total</span>
                    <span>{{ number_format($grandTotal,2) }} {{ $currency }}</span>
                </div>

                <button class="btn-checkout">
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
            --primary: #0f172a;
            --primary-light: #1e293b;
            --accent: #3b82f6;
            --accent-light: #60a5fa;
            --accent-glow: rgba(59, 130, 246, 0.4);
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --danger: #f87171;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Navbar ── */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.15);
            padding: 14px 0;
        }

        .navbar-brand-text {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-link-custom {
            color: var(--text-muted) !important;
            font-weight: 500;
            transition: 0.3s;
            position: relative;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: var(--accent) !important;
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .cart-badge {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50px;
            position: absolute;
            top: -2px;
            right: -8px;
        }

        /* ── Alerts ── */
        .alert-custom-success {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .alert-custom-danger {
            background: rgba(248, 113, 113, 0.1);
            color: var(--danger);
            border: 1px solid rgba(248, 113, 113, 0.25);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        /* ── Gradient Text ── */
        .gradient-text {
            background: linear-gradient(135deg, var(--accent), var(--accent-light), #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ── Progress Steps ── */
        .checkout-steps {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 2.5rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .step.completed .step-circle {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .step.active .step-circle {
            background: var(--primary);
            color: var(--accent);
            border: 2px solid var(--accent);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .step.inactive .step-circle {
            background: var(--primary-light);
            color: var(--text-muted);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .step-label {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .step.completed .step-label { color: var(--accent); }
        .step.active .step-label { color: var(--text); }
        .step.inactive .step-label { color: var(--text-muted); }

        .step-line {
            width: 60px;
            height: 2px;
            margin: 0 6px;
            border-radius: 2px;
        }

        .step-line.done { background: var(--accent); }
        .step-line.pending { background: rgba(148, 163, 184, 0.2); }

        /* ── Cards ── */
        .dark-card {
            background: var(--primary-light);
            border-radius: 20px;
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(59, 130, 246, 0.05);
            overflow: hidden;
            transition: 0.3s;
        }

        .dark-card:hover {
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 0 30px rgba(59, 130, 246, 0.08);
            border-color: rgba(59, 130, 246, 0.15);
        }

        .card-header-dark {
            background: var(--primary);
            color: var(--accent);
            font-weight: 700;
            padding: 16px 20px;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-dark i {
            font-size: 1.1rem;
        }

        /* ── Billing Form ── */
        .billing-card { padding: 28px; }

        .billing-card h4 {
            font-weight: 800;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .billing-card h4 i {
            color: var(--accent);
        }

        .form-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .form-control-dark {
            background: var(--primary);
            color: var(--text);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: 0.3s;
            width: 100%;
        }

        .form-control-dark::placeholder {
            color: var(--text-muted);
            opacity: 0.5;
        }

        .form-control-dark:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow), 0 0 20px rgba(59, 130, 246, 0.15);
            background: rgba(15, 23, 42, 0.8);
            color: var(--text);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 0.9rem;
            opacity: 0.6;
        }

        .input-icon-wrapper .form-control-dark {
            padding-left: 42px;
        }

        /* ── Cart Items ── */
        .cart-item {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.06);
            transition: 0.2s;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            background: rgba(59, 130, 246, 0.03);
        }

        .cart-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 14px;
            margin-right: 14px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .cart-item-name {
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 2px;
        }

        .cart-item-meta {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .cart-item-price {
            font-weight: 700;
            color: var(--accent-light);
            white-space: nowrap;
        }

        /* ── Payment ── */
        .payment-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 14px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .payment-option::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), transparent);
            opacity: 0;
            transition: 0.3s;
        }

        .payment-option:hover {
            border-color: rgba(59, 130, 246, 0.4);
            background: rgba(15, 23, 42, 0.5);
        }

        .payment-option:hover::before {
            opacity: 1;
        }

        .payment-option.selected {
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.08);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.15);
        }

        .payment-option input[type="radio"] {
            accent-color: var(--accent);
            width: 18px;
            height: 18px;
        }

        .payment-option i {
            font-size: 1.3rem;
            color: var(--accent);
            width: 24px;
            text-align: center;
        }

        .payment-label-text {
            flex: 1;
        }

        .payment-label-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* ── Summary ── */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.92rem;
        }

        .summary-row span:first-child {
            color: var(--text-muted);
        }

        .summary-row span:last-child {
            font-weight: 600;
        }

        .summary-divider {
            border: none;
            border-top: 1px solid rgba(59, 130, 246, 0.15);
            margin: 14px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--accent);
        }

        /* ── Button ── */
        .btn-checkout {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary);
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--accent-glow);
        }

        .btn-checkout:hover::before {
            left: 100%;
        }

        .btn-checkout:active {
            transform: translateY(0);
        }

        /* ── Sticky Sidebar ── */
        .sticky-sidebar {
            position: sticky;
            top: 90px;
        }

        /* ── Security Badge ── */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 0.78rem;
            margin-top: 14px;
        }

        .security-badge i {
            color: #22c55e;
        }

        /* ── Coupon ── */
        .coupon-wrapper {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .coupon-wrapper input {
            flex: 1;
        }

        .btn-coupon {
            padding: 10px 20px;
            border: 1px solid var(--accent);
            background: transparent;
            color: var(--accent);
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.3s;
            white-space: nowrap;
        }

        .btn-coupon:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        /* ── Empty Cart ── */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-cart-icon {
            font-size: 5rem;
            color: var(--text-muted);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-cart h4 {
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .btn-continue {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: var(--primary-light);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-continue:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ── Footer ── */
        .footer-dark {
            background: var(--primary);
            border-top: 1px solid rgba(59, 130, 246, 0.08);
            padding: 30px 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 60px;
        }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sticky-sidebar {
                position: static;
            }

            .checkout-steps {
                flex-wrap: wrap;
                gap: 4px;
            }

            .step-line {
                width: 30px;
            }

            .step-label {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .gradient-text {
                font-size: 1.5rem;
            }

            .billing-card {
                padding: 20px;
            }

            .cart-img {
                width: 48px;
                height: 48px;
            }
        }

        /* ── Glow Orbs Background ── */
        .bg-orbs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-orbs .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.07;
        }

        .bg-orbs .orb-1 {
            width: 500px;
            height: 500px;
            background: var(--accent);
            top: -150px;
            right: -100px;
        }

        .bg-orbs .orb-2 {
            width: 400px;
            height: 400px;
            background: #8b5cf6;
            bottom: -100px;
            left: -100px;
        }

        .bg-orbs .orb-3 {
            width: 300px;
            height: 300px;
            background: var(--accent-light);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .main-content {
            position: relative;
            z-index: 1;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--primary); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 10px; }
    </style>

@endsection