@extends('frontend.app')

@section('content')

@php
use App\Models\Setting;

$settings = Setting::first();
$delivery = $settings?->delivery_charge ?? 0;
$taxPercent = $settings?->tax_percentage ?? 0;
$currency = $settings?->currency ?? '৳';
$subtotal = 0;
@endphp

{{-- Alerts --}}
@if (session('success'))
<div class="alert alert-success m-3">
    {{ session('success') }}
    <button class="close-btn" data-bs-dismiss="alert">&times;</button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger m-3">
    {{ session('error') }}
    <button class="close-btn" data-bs-dismiss="alert">&times;</button>
</div>
@endif

<div class="container">

    <!-- ===== PAGE HEADER ===== -->
    <div class="page-header">
        <h1>
            <i class="bi bi-cart3"></i>
            Ordered Pack
        </h1>
        <p>Review your packs before confirming</p>
    </div>

    <div class="cart-layout">

        <!-- ================= CART TABLE ================= -->
        <div class="cart-card">
            <div class="cart-table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Pack Price</th>
                            <th class="text-end">Package</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($carts as $cart)
                    @php
                        $pack = $cart->pack;
                        if(!$pack) continue;

                        $discount = $pack->discount ?? 0;
                        $priceAfterDiscount = $pack->pack_price * (100 - $discount) / 100;

                        $total = 0;
                        if($pack->stock > 0){
                            $total = $priceAfterDiscount * $cart->quantity;
                            $subtotal += $total;
                        }
                    @endphp

                    <tr class="{{ $pack->stock <= 0 ? 'out-of-stock' : '' }}">

                        <td data-label="SL">
                            <span class="sl-number">{{ $loop->iteration }}</span>
                        </td>
                        <td data-label="Name">
                            <div class="product-name">{{ $pack->name }}</div>
                        </td>
                        <td data-label="Category">
                            <div class="product-category">{{ $pack->PackCategory->name ?? 'N/A' }}</div>
                        </td>
                        <td data-label="Type" class="text-center">
                            <span class="type-badge">{{ $pack->type }}</span>
                        </td>
                            <td data-label="Pack Price" class="text-center price-cell">
                                @if($discount > 0)
                                    <span class="price-original">
                                        {{ number_format($pack->pack_price,2) }} {{ $currency }}
                                    </span>
                                    <span class="price-discount">
                                        {{ number_format($priceAfterDiscount,2) }} {{ $currency }}
                                    </span>
                                @else
                                    <span>
                                        {{ number_format($pack->pack_price,2) }} {{ $currency }}
                                    </span>
                                @endif
                            </td>

                        <td data-label="Pack" class="text-center">
                            <img src="{{ config('app.storage_url') }}{{ $pack->image }}"
                                 class="cart-product-image">
                        </td>
                        <td data-label="Action" class="text-center">
                            <a href="/manage/destroy/{{ $cart->id }}" class="remove-btn">
                                Remove
                            </a>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-cart">
                                <i class="bi bi-cart-x"></i>
                                <h3>Your cart is empty</h3>
                                <p>Add some packs to continue order</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= SUMMARY ================= -->
        @if($carts->count() > 0)
        @php
            $taxAmount = ($subtotal * $taxPercent) / 100;
            $grandTotal = $subtotal + $taxAmount + $delivery;
        @endphp

        <div class="summary-card">

            <h5><i class="bi bi-receipt"></i> Order Summary</h5>

            @foreach($carts as $cart)
            @php
                $pack = $cart->pack;
                if(!$pack) continue;
                $priceAfterDiscount = $pack->pack_price * (100 - ($pack->discount ?? 0)) / 100;
            @endphp

            <div class="summary-item">
                <div class="summary-item-left">
                    <img src="{{ config('app.storage_url') }}{{ $pack->image }}"
                         class="mini-cart-img">

                    <div>
                        <div class="summary-item-name">{{ $pack->name }}</div>
                        <div class="summary-item-detail">
                        {{ number_format($priceAfterDiscount,2) }} {{ $currency }}
                        </div>
                    </div>
                </div>

                <a href="/manage/destroy/{{ $cart->id }}" class="remove-btn-mini">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
            @endforeach

            <hr class="summary-divider">

            <div class="summary-total">
                <span class="label">Grand Total</span>
                <span class="value">{{ number_format($priceAfterDiscount,2) }} {{ $currency }}</span>
            </div>

            <a href="/billing" class="btn-checkout">
                <i class="bi bi-credit-card"></i>
                Proceed to Checkout
            </a>

        </div>
        @endif

    </div>
</div>

<style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(20px);
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            color: #3b82f6;
            font-size: 1.8rem;
        }

        .navbar-brand span {
            background: linear-gradient(135deg, #3b82f6, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #3b82f6;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            right: 0;
            height: 2px;
            background: #3b82f6;
            border-radius: 2px;
        }

        .nav-cart-badge {
            position: relative;
        }

        .nav-cart-badge .badge-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #3b82f6;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
            font-size: 1.4rem;
            padding: 6px 10px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            padding: 40px 0 10px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-header h1 i {
            color: #3b82f6;
            font-size: 2.2rem;
        }

        .page-header p {
            color: #64748b;
            margin-top: 6px;
            font-size: 0.95rem;
        }

        .page-header .item-count {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 500;
            animation: slideDown 0.4s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(16, 185, 129, 0.15));
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .alert .close-btn {
            background: none;
            border: none;
            color: inherit;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .alert .close-btn:hover {
            opacity: 1;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== LAYOUT GRID ===== */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 28px;
            padding: 20px 0 60px;
        }

        /* ===== CART CARD ===== */
        .cart-card {
            border-radius: 18px;
            background: linear-gradient(145deg, #1e293b, #162032);
            border: 1px solid rgba(59, 130, 246, 0.1);
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        /* ===== TABLE ===== */
        .cart-table-wrap {
            overflow-x: auto;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table thead tr {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.03));
            border-bottom: 2px solid rgba(59, 130, 246, 0.25);
        }

        .cart-table th {
            padding: 16px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #3b82f6;
            white-space: nowrap;
        }

        .cart-table td {
            padding: 16px 14px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 0.9rem;
        }

        .cart-table tbody tr {
            background: #1e293b;
            transition: all 0.3s ease;
        }

        .cart-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.06);
        }

        .cart-table tbody tr:nth-child(even) {
            background: #1a2537;
        }

        .cart-table tbody tr:nth-child(even):hover {
            background: rgba(59, 130, 246, 0.06);
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }

        /* ===== PRODUCT IMAGE ===== */
        .cart-product-image {
            width: 65px;
            height: 65px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(59, 130, 246, 0.15);
            transition: transform 0.3s, border-color 0.3s;
        }

        .cart-product-image:hover {
            transform: scale(1.08);
            border-color: #3b82f6;
        }

        .product-name {
            font-weight: 600;
            color: #fff;
            font-size: 0.92rem;
        }

        .product-category {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 2px;
        }

        /* ===== PRICE ===== */
        .price-cell {
            font-weight: 600;
            color: #e2e8f0;
        }

        .price-original {
            text-decoration: line-through;
            color: #64748b;
            font-size: 0.78rem;
            display: block;
        }

        .price-discount {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* ===== STOCK BADGE ===== */
        .stock-badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-block;
        }

        .stock-in {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .stock-low {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        .stock-out {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        /* ===== QTY CONTROL ===== */
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1.5px solid rgba(59, 130, 246, 0.35);
            background: rgba(59, 130, 246, 0.08);
            color: #3b82f6;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s;
            text-decoration: none;
        }

        .qty-btn:hover {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
            transform: scale(1.1);
        }

        .qty-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
        }

        /* ===== TOTAL ===== */
        .total-price {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .total-in-stock {
            color: #10b981;
        }

        .total-out-stock {
            color: #ef4444;
        }

        /* ===== OUT OF STOCK ROW ===== */
        .out-of-stock {
            opacity: 0.55;
            position: relative;
        }

        .out-of-stock::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(239, 68, 68, 0.04);
            pointer-events: none;
        }

        .out-of-stock-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== REMOVE BUTTON ===== */
        .remove-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .remove-btn:hover {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ===== EMPTY CART ===== */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart i {
            font-size: 4rem;
            color: rgba(59, 130, 246, 0.25);
            margin-bottom: 16px;
        }

        .empty-cart h3 {
            color: #3b82f6;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .empty-cart p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .empty-cart .shop-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }

        .empty-cart .shop-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        }

        /* ===== SUMMARY CARD ===== */
        .summary-card {
            background: linear-gradient(145deg, #1e293b, #162032);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 18px;
            padding: 24px;
            position: sticky;
            top: 90px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .summary-card h5 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-card h5 i {
            color: #3b82f6;
        }

        /* ===== SUMMARY ITEMS ===== */
        .summary-item {
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: all 0.3s;
        }

        .summary-item:hover {
            border-color: rgba(59, 130, 246, 0.2);
            background: rgba(59, 130, 246, 0.05);
        }

        .summary-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mini-cart-img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            border: 1.5px solid rgba(59, 130, 246, 0.15);
        }

        .summary-item-name {
            font-weight: 600;
            font-size: 0.88rem;
            color: #e2e8f0;
        }

        .summary-item-detail {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 2px;
        }

        .remove-btn-mini {
            color: #ef4444;
            font-size: 1.2rem;
            opacity: 0.6;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
        }

        .remove-btn-mini:hover {
            opacity: 1;
            transform: scale(1.15);
        }

        /* ===== DIVIDER ===== */
        .summary-divider {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, #3b82f6, transparent);
            margin: 18px 0;
            opacity: 0.4;
        }

        /* ===== SUMMARY ROWS ===== */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .summary-row .label {
            color: #94a3b8;
        }

        .summary-row .value {
            color: #e2e8f0;
            font-weight: 600;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 2px dashed rgba(59, 130, 246, 0.3);
        }

        .summary-total .label {
            font-size: 1.05rem;
            font-weight: 800;
            color: #fff;
        }

        .summary-total .value {
            font-size: 1.15rem;
            font-weight: 800;
            background: linear-gradient(135deg, #10b981, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== CHECKOUT BUTTON ===== */
        .btn-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            margin-top: 20px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.35s;
            text-decoration: none;
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s;
        }

        .btn-checkout:hover::before {
            left: 100%;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
        }

        /* ===== COUPON ===== */
        .coupon-section {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .coupon-input-group {
            display: flex;
            gap: 8px;
        }

        .coupon-input {
            flex: 1;
            padding: 12px 16px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 10px;
            color: #e2e8f0;
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .coupon-input::placeholder {
            color: #475569;
        }

        .coupon-input:focus {
            border-color: #3b82f6;
        }

        .coupon-btn {
            padding: 12px 20px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            color: #3b82f6;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .coupon-btn:hover {
            background: #3b82f6;
            color: #fff;
        }

        /* ===== SECURITY BADGES ===== */
        .security-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            color: #64748b;
        }

        .security-badge i {
            color: #3b82f6;
            font-size: 0.85rem;
        }

        /* ===== SL NUMBER ===== */
        .sl-number {
            width: 28px;
            height: 28px;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #0a0f1a;
            border-top: 1px solid rgba(59, 130, 246, 0.1);
            padding: 30px 0;
            text-align: center;
            color: #475569;
            font-size: 0.85rem;
        }

        .footer a {
            color: #3b82f6;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: #60a5fa;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 1024px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav-links.show {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #1e293b;
                padding: 20px;
                border-bottom: 1px solid rgba(59, 130, 246, 0.15);
                gap: 16px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .page-header h1 i {
                font-size: 1.6rem;
            }

            /* Card-based mobile layout */
            .cart-table-wrap table,
            .cart-table-wrap thead,
            .cart-table-wrap tbody,
            .cart-table-wrap th,
            .cart-table-wrap td,
            .cart-table-wrap tr {
                display: block;
                width: 100%;
            }

            .cart-table-wrap thead {
                display: none;
            }

            .cart-table-wrap tbody tr {
                background: #1e293b !important;
                border-radius: 16px;
                padding: 16px;
                margin-bottom: 14px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
                border: 1px solid rgba(59, 130, 246, 0.08);
                position: relative;
            }

            .cart-table-wrap td {
                text-align: right;
                padding: 10px 12px;
                position: relative;
                border: none;
                font-size: 0.88rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .cart-table-wrap td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #3b82f6;
                text-align: left;
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .cart-product-image {
                width: 55px;
                height: 55px;
            }

            .qty-badge {
                min-width: 34px;
                padding: 4px 10px;
            }

            .summary-card {
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 14px;
            }

            .page-header {
                padding: 24px 0 6px;
            }

            .page-header h1 {
                font-size: 1.25rem;
            }

            .summary-card {
                padding: 18px;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cart-card {
            animation: fadeInUp 0.5s ease;
        }

        .summary-card {
            animation: fadeInUp 0.6s ease 0.1s both;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* ===== GLOW EFFECT ===== */
        .glow-blue {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.08);
        }
    </style>

@endsection