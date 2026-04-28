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

<div class="container">

    <!-- Header -->
    <div class="page-header">
        <h1><i class="bi bi-cart3"></i> IRON PULSE GYM</h1>
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
                            <th class="text-center">Package</th>
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

                        $lineTotal = $priceAfterDiscount * $cart->quantity;
                        $subtotal += $lineTotal;
                    @endphp

                    <tr>
                        <td><span class="sl-number">{{ $loop->iteration }}</span></td>

                        <td>
                            <div class="product-name">{{ $pack->name }}</div>
                        </td>

                        <td>
                            <div class="product-category">
                                {{ $pack->PackCategory->name ?? 'N/A' }}
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="type-badge">{{ $pack->type }}</span>
                        </td>

                        <td class="text-center price-cell">
                            @if($discount > 0)
                                <span class="price-original">
                                    {{ number_format($pack->pack_price,2) }} {{ $currency }}
                                </span>
                                <span class="price-discount">
                                    {{ number_format($priceAfterDiscount,2) }} {{ $currency }}
                                </span>
                            @else
                                {{ number_format($pack->pack_price,2) }} {{ $currency }}
                            @endif
                        </td>

                        <td class="text-center">
                            <img src="{{ config('app.storage_url') }}{{ $pack->image }}"
                                 class="cart-product-image">
                        </td>

                        <td class="text-center">
                            <a href="{{ route('cart.destroy', $cart->id) }}"
                               class="remove-btn">
                                Remove
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-cart text-center">
                                <i class="bi bi-cart-x"></i>
                                <h3>Your cart is empty</h3>
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

                <a href="{{ route('cart.destroy', $cart->id) }}"
                   class="remove-btn-mini">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>

            @endforeach

            <hr class="summary-divider">

            <!-- Totals -->
            <div class="summary-item d-flex justify-content-between">
                <span>Subtotal</span>
                <span>{{ number_format($subtotal,2) }} {{ $currency }}</span>
            </div>

            <div class="summary-item d-flex justify-content-between">
                <span>Tax ({{ $taxPercent }}%)</span>
                <span>{{ number_format($taxAmount,2) }} {{ $currency }}</span>
            </div>

            <div class="summary-item d-flex justify-content-between">
                <span>Delivery</span>
                <span>{{ number_format($delivery,2) }} {{ $currency }}</span>
            </div>

            <div class="summary-total">
                <span class="label">Grand Total</span>
                <span class="value">
                    {{ number_format($grandTotal,2) }} {{ $currency }}
                </span>
            </div>

            <a href="{{ route('billing') }}" class="btn-checkout">
                <i class="bi bi-credit-card"></i>
                Proceed to Checkout
            </a>

        </div>

        @endif

    </div>
</div>

<script>
        // Remove item from cart table
        function removeItem(event, element) {
            event.preventDefault();
            const row = element.closest('tr');
            row.style.animation = 'fadeOut 0.5s ease';
            setTimeout(() => {
                row.remove();
                updateTotal();
                checkEmptyCart();
            }, 500);
        }

        // Remove item from summary
        function removeFromSummary(event, element) {
            event.preventDefault();
            const item = element.closest('.summary-item');
            item.style.animation = 'fadeOut 0.5s ease';
            setTimeout(() => {
                item.remove();
                updateTotal();
                checkEmptyCart();
            }, 500);
        }

        // Update grand total
        function updateTotal() {
            const summaryItems = document.querySelectorAll('.summary-item-detail');
            let total = 0;
            summaryItems.forEach(item => {
                const price = parseFloat(item.textContent.replace(/[^\d.]/g, ''));
                total += price;
            });
            document.getElementById('grandTotal').textContent = total.toFixed(2) + ' ৳';
        }

        // Check if cart is empty
        function checkEmptyCart() {
            const cartRows = document.querySelectorAll('.cart-table tbody tr');
            if (cartRows.length === 0) {
                const tbody = document.querySelector('.cart-table tbody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7">
                            <div class="empty-cart">
                                <i class="bi bi-cart-x"></i>
                                <h3>Your cart is empty</h3>
                                <p>Add some packs to continue order</p>
                            </div>
                        </td>
                    </tr>
                `;
                document.querySelector('.summary-card').style.display = 'none';
            }
        }

        // Checkout function
        function checkout(event) {
            event.preventDefault();
            alert('Proceeding to checkout... 💪');
        }

        // Add fadeOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: scale(1);
                }
                to {
                    opacity: 0;
                    transform: scale(0.8);
                }
            }
        `;
        document.head.appendChild(style);
    </script>

     <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
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
            overflow-x: hidden;
        }

        /* Animated Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(90deg, transparent, transparent 50px, rgba(227, 9, 20, 0.03) 50px, rgba(227, 9, 20, 0.03) 51px),
                repeating-linear-gradient(0deg, transparent, transparent 50px, rgba(227, 9, 20, 0.03) 50px, rgba(227, 9, 20, 0.03) 51px);
            z-index: 0;
            pointer-events: none;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideInDown 0.5s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #1a4d2e 0%, #2d5f3f 100%);
            border-left: 4px solid #4ade80;
        }

        .alert-danger {
            background: linear-gradient(135deg, #4d1a1a 0%, #5f2d2d 100%);
            border-left: 4px solid var(--secondary);
        }

        .close-btn {
            background: none;
            border: none;
            color: var(--text);
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .close-btn:hover {
            transform: rotate(90deg);
        }

        /* Page Header with Gym Theme */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            padding: 2rem 0;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(229, 9, 20, 0.15) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
            z-index: -1;
        }

        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: slideInDown 0.8s ease;
        }

        .page-header h1 i {
            color: var(--secondary);
            -webkit-text-fill-color: var(--secondary);
            animation: bounce 2s ease-in-out infinite;
        }

        .page-header p {
            font-size: 1.2rem;
            color: #cccccc;
            letter-spacing: 1px;
        }

        /* Cart Layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-top: 2rem;
        }

        /* Cart Card */
        .cart-card {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.9) 0%, rgba(30, 30, 30, 0.9) 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(229, 9, 20, 0.2);
            animation: fadeInLeft 0.8s ease;
        }

        .cart-table-wrap {
            overflow-x: auto;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table thead {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        }

        .cart-table thead th {
            padding: 1.2rem 1rem;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            border-bottom: 3px solid var(--primary);
        }

        .cart-table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .cart-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .cart-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .cart-table tbody tr:nth-child(3) { animation-delay: 0.3s; }

        .cart-table tbody tr:hover {
            background: rgba(229, 9, 20, 0.1);
            transform: scale(1.01);
        }

        .cart-table tbody tr.out-of-stock {
            opacity: 0.5;
            background: rgba(100, 100, 100, 0.1);
        }

        .cart-table tbody td {
            padding: 1.5rem 1rem;
        }

        .sl-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.1rem;
            animation: rotate360 20s linear infinite;
        }

        .product-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text);
        }

        .product-category {
            color: var(--accent);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .type-badge {
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.3) 0%, rgba(255, 59, 59, 0.3) 100%);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid var(--accent);
            display: inline-block;
        }

        .price-cell {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .price-original {
            text-decoration: line-through;
            color: #888;
            display: block;
            font-size: 0.9rem;
        }

        .price-discount {
            color: var(--accent);
            font-size: 1.2rem;
            display: block;
            font-weight: 800;
        }

        .cart-product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--accent);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        .cart-product-image:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(229, 9, 20, 0.5);
        }

        .remove-btn {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: var(--text);
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border: 1px solid var(--accent);
        }

        .remove-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(229, 9, 20, 0.4);
            background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-cart i {
            font-size: 5rem;
            color: var(--secondary);
            margin-bottom: 1rem;
            animation: shake 2s ease-in-out infinite;
        }

        .empty-cart h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .empty-cart p {
            color: #888;
            font-size: 1.1rem;
        }

        /* Summary Card */
        .summary-card {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.95) 0%, rgba(30, 30, 30, 0.95) 100%);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(229, 9, 20, 0.3);
            position: sticky;
            top: 2rem;
            animation: fadeInRight 0.8s ease;
            height: fit-content;
        }

        .summary-card h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-card h5 i {
            color: var(--secondary);
            animation: pulse 2s ease-in-out infinite;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideInRight 0.5s ease;
        }

        .summary-item-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mini-cart-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--accent);
        }

        .summary-item-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-item-detail {
            color: var(--accent);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .remove-btn-mini {
            color: var(--secondary);
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .remove-btn-mini:hover {
            color: var(--accent);
            transform: rotate(90deg);
        }

        .summary-divider {
            border: none;
            border-top: 2px solid rgba(229, 9, 20, 0.3);
            margin: 1.5rem 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .summary-total .label {
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-total .value {
            color: var(--accent);
            font-size: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .btn-checkout {
            width: 100%;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: var(--text);
            padding: 1.2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            border: 2px solid var(--accent);
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
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-checkout:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.5);
        }

        .btn-checkout i {
            position: relative;
            z-index: 1;
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        @keyframes rotate360 {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Power Bar Animation (Decorative) */
        .power-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
            z-index: 1000;
        }

        .power-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--secondary) 0%, var(--accent) 100%);
            animation: loadPower 3s ease-in-out infinite;
        }

        @keyframes loadPower {
            0% { width: 0%; }
            50% { width: 100%; }
            100% { width: 0%; }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .cart-table {
                font-size: 0.9rem;
            }

            .cart-table thead {
                display: none;
            }

            .cart-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid rgba(229, 9, 20, 0.3);
                border-radius: 12px;
                padding: 1rem;
            }

            .cart-table tbody td {
                display: block;
                text-align: left !important;
                padding: 0.5rem 0;
                border: none;
            }

            .cart-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                display: inline-block;
                width: 120px;
                color: var(--accent);
            }

            .cart-product-image {
                width: 100%;
                max-width: 200px;
                height: auto;
            }
        }
    </style>

@endsection