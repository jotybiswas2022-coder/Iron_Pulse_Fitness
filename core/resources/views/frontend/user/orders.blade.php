@extends('frontend.app')

@section('content')

@php
use App\Models\Setting;

$settings = Setting::first();
$currency = $settings?->currency ?? '৳';
@endphp

<!-- Gym Decorative Elements -->
<i class="bi bi-activity gym-dumbbell"></i>
<i class="bi bi-heart-pulse gym-dumbbell"></i>
<i class="bi bi-lightning-charge gym-dumbbell"></i>

{{-- Success Alert --}}
@if (session('success'))
<div class="alert alert-success m-3">
    {{ session('success') }}
</div>
@endif

<div class="container py-4">

    <div class="orders-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-lightning-charge-fill"></i> My Ordered Packs
            </h2>
            <p class="mb-0" style="color: var(--accent); font-weight: 600;">
                Iron Pulse Gym - Power Your Journey
            </p>
        </div>

        <div class="search-box">
            <input type="text" id="orderSearch" placeholder="Search orders...">
            <i class="bi bi-search"></i>
        </div>
    </div>

    <div class="orders-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center table-dark" id="ordersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pack Name</th>
                        <th>Price</th>
                        <th>Remaining Time</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="ordersTableBody">
                    @php $sl = 1; @endphp

                    @forelse($orders as $order)
                        @foreach($order->orderdetails as $pack)

                        @php
                            $packName = $pack->pack_name ?? '-';
                            $packPrice = (float) ($pack->pack_price ?? 0);
                            $status = strtolower((string) ($pack->status ?? 'processing'));

                            $statusClass = match($status) {
                                'pending' => 'status-pending',
                                'processing' => 'status-processing',
                                'approve', 'approved', 'delivered' => 'status-approved',
                                'canceled', 'cancelled' => 'status-cancelled',
                                default => 'status-processing'
                            };

                            $method = strtolower((string) ($order->payment_method ?? ''));

                            $methodClass = match($method) {
                                'cod' => 'method-cod',
                                'bkash' => 'method-bkash',
                                'nagad' => 'method-nagad',
                                default => 'method-cod'
                            };

                            $methodLabel = match($method) {
                                'cod' => 'Cash on Delivery',
                                'bkash' => 'BKash',
                                'nagad' => 'Nagad',
                                default => ucfirst($method ?: 'Cash on Delivery')
                            };

                            $endTime = null;
                            if ($order->approved_at) {
                                $days = match($pack->pack_id) {
                                    1 => 30,
                                    3 => 180,
                                    2 => 365,
                                    default => 0
                                };

                                if ($days > 0) {
                                    $endTime = \Carbon\Carbon::parse($order->approved_at)->addDays($days);
                                }
                            }
                        @endphp

                        <tr class="order-row">
                            <td data-label="#">
                                <span class="sl-number">{{ $sl++ }}</span>
                            </td>

                            <td class="product-name">{{ $packName }}</td>

                            <td class="price-cell">
                                {{ $currency }} {{ number_format($packPrice, 2) }}
                            </td>

                            <td>
                                @if($endTime)
                                    <span class="countdown" data-end="{{ $endTime->toIso8601String() }}">Loading...</span>
                                @else
                                    <span class="text-muted">Not started</span>
                                @endif
                            </td>

                            <td>
                                <span class="method-badge {{ $methodClass }}">
                                    {{ $methodLabel }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td>
                                <button class="view-order-btn"
                                    data-name="{{ $packName }}"
                                    data-price="{{ $currency }} {{ number_format($packPrice, 2) }}"
                                    data-qty="1"
                                    data-total="{{ $currency }} {{ number_format($packPrice, 2) }}"
                                    data-status="{{ ucfirst($status) }}"
                                    data-statusclass="{{ $statusClass }}"
                                    data-firstname="{{ $order->firstname }}"
                                    data-lastname="{{ $order->lastname }}"
                                    data-email="{{ $order->email }}"
                                    data-phone="{{ $order->phone }}"
                                    data-address="{{ $order->address }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#orderdetailsmodal">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        @endforeach

                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-bag-x"></i></div>
                                    No orders found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orderdetailsmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content order-modal">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text"></i> Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <h6 id="modalName" class="mb-3 fw-bold"></h6>

                <div class="row g-3 small">
                    <div class="col-6">
                        <div>Price</div>
                        <div id="modalPrice"></div>
                    </div>
                    <div class="col-6">
                        <div>Qty</div>
                        <div id="modalQty"></div>
                    </div>
                    <div class="col-6">
                        <div>Total</div>
                        <div id="modalTotal" class="text-success fw-bold"></div>
                    </div>
                    <div class="col-6">
                        <div>Status</div>
                        <div id="modalStatus"></div>
                    </div>
                </div>

                <hr>

                <div class="small">
                    <div id="modalCustomer"></div>
                    <div id="modalEmail"></div>
                    <div id="modalPhone"></div>
                    <div id="modalAddress"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
document.getElementById('orderSearch').addEventListener('keyup', function () {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#ordersTable tbody .order-row').forEach((row) => {
        row.style.display = row.innerText.toLowerCase().includes(search) ? '' : 'none';
    });
});

document.querySelectorAll('.view-order-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
        document.getElementById('modalName').innerText = this.dataset.name;
        document.getElementById('modalPrice').innerText = this.dataset.price;
        document.getElementById('modalQty').innerText = this.dataset.qty;
        document.getElementById('modalTotal').innerText = this.dataset.total;

        document.getElementById('modalCustomer').innerText =
            this.dataset.firstname + ' ' + this.dataset.lastname;

        document.getElementById('modalEmail').innerText = this.dataset.email;
        document.getElementById('modalPhone').innerText = this.dataset.phone;
        document.getElementById('modalAddress').innerText = this.dataset.address;

        document.getElementById('modalStatus').innerHTML =
            '<span class="status-badge ' + this.dataset.statusclass + '">' + this.dataset.status + '</span>';
    });
});

function startCountdown() {
    document.querySelectorAll('.countdown').forEach((el) => {
        const endTime = Date.parse(el.dataset.end);

        const tick = () => {
            const diff = endTime - Date.now();

            if (diff <= 0) {
                el.innerHTML = 'Expired';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diff / (1000 * 60)) % 60);
            const seconds = Math.floor((diff / 1000) % 60);

            el.innerHTML = days+'d '+hours+'h '+minutes+'m '+seconds+'s';
        };

        tick();
        setInterval(tick, 1000);
    });
}

startCountdown();
</script>

<style>
        :root {
            --deep-black: #0F0F0F;
            --power-red: #E50914;
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
                radial-gradient(circle at 80% 80%, rgba(255, 59, 59, 0.08) 0%, transparent 50%);
            animation: pulseBackground 8s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes pulseBackground {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Power Lines Animation */
        body::after {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(229, 9, 20, 0.03) 50%,
                transparent 70%
            );
            animation: powerLines 15s linear infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes powerLines {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(50%, 50%) rotate(360deg); }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        /* Header Section */
        .orders-header {
            background: linear-gradient(135deg, var(--deep-black) 0%, rgba(229, 9, 20, 0.2) 100%);
            padding: 2rem;
            border-radius: 15px;
            border: 2px solid var(--power-red);
            box-shadow: 0 0 30px rgba(229, 9, 20, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .orders-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 59, 59, 0.1),
                transparent
            );
            animation: headerShine 3s linear infinite;
        }

        @keyframes headerShine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .orders-header h2 {
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--text);
            text-shadow: 0 0 20px var(--power-red);
            letter-spacing: 2px;
            position: relative;
            animation: textPulse 2s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% { text-shadow: 0 0 20px var(--power-red); }
            50% { text-shadow: 0 0 30px var(--power-red), 0 0 40px var(--accent); }
        }

        /* Search Box */
        .search-box {
            position: relative;
            animation: slideLeft 0.8s ease-out;
        }

        @keyframes slideLeft {
            from {
                transform: translateX(50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .search-box input {
            background: var(--deep-black);
            border: 2px solid var(--power-red);
            color: var(--text);
            padding: 12px 45px 12px 20px;
            border-radius: 50px;
            width: 300px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.2);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 25px rgba(255, 59, 59, 0.5);
            transform: scale(1.05);
        }

        .search-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--power-red);
            font-size: 1.2rem;
            animation: searchPulse 2s ease-in-out infinite;
        }

        @keyframes searchPulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.2); }
        }

        /* Orders Card */
        .orders-card {
            background: linear-gradient(135deg, var(--deep-black) 0%, #1a1a1a 100%);
            border-radius: 15px;
            padding: 2rem;
            border: 2px solid var(--power-red);
            box-shadow: 0 10px 40px rgba(229, 9, 20, 0.3);
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .orders-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(229, 9, 20, 0.1),
                transparent
            );
            animation: cardSweep 3s ease-in-out infinite;
        }

        @keyframes cardSweep {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        /* Table Styles */
        .table-dark {
            background-color: transparent !important;
            color: var(--text);
        }

        .table-dark thead th {
            background: linear-gradient(135deg, var(--power-red) 0%, var(--accent) 100%);
            color: var(--text);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 1.2rem;
            position: relative;
            overflow: hidden;
        }

        .table-dark thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--text);
            animation: headerGlow 2s ease-in-out infinite;
        }

        @keyframes headerGlow {
            0%, 100% { box-shadow: 0 0 5px var(--text); }
            50% { box-shadow: 0 0 15px var(--text); }
        }

        .table-dark tbody tr {
            border-bottom: 1px solid rgba(229, 9, 20, 0.2);
            transition: all 0.3s ease;
            animation: rowFadeIn 0.5s ease-out backwards;
        }

        .table-dark tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table-dark tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .table-dark tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .table-dark tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .table-dark tbody tr:nth-child(5) { animation-delay: 0.5s; }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .table-dark tbody tr:hover {
            background: rgba(229, 9, 20, 0.1);
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(229, 9, 20, 0.3);
        }

        .table-dark td {
            padding: 1.2rem;
            vertical-align: middle;
            border: none;
        }

        .sl-number {
            display: inline-block;
            background: linear-gradient(135deg, var(--power-red), var(--accent));
            color: var(--text);
            width: 35px;
            height: 35px;
            line-height: 35px;
            border-radius: 50%;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.5);
            animation: numberPulse 2s ease-in-out infinite;
        }

        @keyframes numberPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); box-shadow: 0 0 25px rgba(229, 9, 20, 0.8); }
        }

        .product-name {
            font-weight: 600;
            color: var(--accent);
            font-size: 1.1rem;
        }

        .price-cell {
            font-weight: 700;
            color: #4ade80;
            font-size: 1.1rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            animation: badgePulse 2s ease-in-out infinite;
        }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: var(--deep-black);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
        }

        .status-processing {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: var(--text);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }

        .status-approved {
            background: linear-gradient(135deg, #10b981, #4ade80);
            color: var(--deep-black);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
        }

        .status-cancelled {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: var(--text);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
        }

        /* Method Badges */
        .method-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .method-cod {
            background: rgba(229, 9, 20, 0.2);
            color: var(--accent);
            border: 1px solid var(--accent);
        }

        .method-bkash {
            background: rgba(231, 0, 115, 0.2);
            color: #E70073;
            border: 1px solid #E70073;
        }

        .method-nagad {
            background: rgba(239, 62, 27, 0.2);
            color: #EF3E1B;
            border: 1px solid #EF3E1B;
        }

        /* View Button */
        .view-order-btn {
            background: linear-gradient(135deg, var(--power-red), var(--accent));
            border: none;
            color: var(--text);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.5);
        }

        .view-order-btn:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 0 30px rgba(255, 59, 59, 0.8);
            animation: buttonShake 0.5s ease-in-out;
        }

        @keyframes buttonShake {
            0%, 100% { transform: scale(1.1) rotate(5deg); }
            25% { transform: scale(1.1) rotate(-5deg); }
            75% { transform: scale(1.1) rotate(5deg); }
        }

        .view-order-btn i {
            font-size: 1.2rem;
        }

        /* Countdown Timer */
        .countdown {
            display: inline-block;
            background: linear-gradient(135deg, var(--deep-black), rgba(229, 9, 20, 0.3));
            padding: 8px 16px;
            border-radius: 25px;
            border: 2px solid var(--power-red);
            font-weight: 700;
            color: var(--accent);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.3);
            animation: timerPulse 1s ease-in-out infinite;
        }

        @keyframes timerPulse {
            0%, 100% { box-shadow: 0 0 15px rgba(229, 9, 20, 0.3); }
            50% { box-shadow: 0 0 25px rgba(255, 59, 59, 0.6); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--power-red);
            margin-bottom: 1rem;
            animation: emptyBounce 2s ease-in-out infinite;
        }

        @keyframes emptyBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Modal Styles */
        .order-modal {
            background: linear-gradient(135deg, var(--deep-black) 0%, #1a1a1a 100%);
            border: 2px solid var(--power-red);
            border-radius: 15px;
            color: var(--text);
            box-shadow: 0 0 50px rgba(229, 9, 20, 0.5);
            animation: modalZoom 0.3s ease-out;
        }

        .order-modal .modal-body {
            color: var(--text);
        }

        .order-modal .modal-body .small > div:first-child,
        .order-modal .modal-body .row.small > div > div:first-child {
            color: #c7c7c7;
        }

        @keyframes modalZoom {
            from {
                transform: scale(0.7);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .order-modal .modal-header {
            border-bottom: 2px solid var(--power-red);
            background: linear-gradient(135deg, var(--power-red), var(--accent));
        }

        .order-modal .modal-title {
            font-weight: 800;
            font-size: 1.8rem;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .order-modal .btn-close {
            background: var(--text);
            opacity: 1;
            border-radius: 50%;
        }

        .modal-info-label {
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            margin-bottom: 0.3rem;
        }

        .modal-info-value {
            color: var(--text);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .modal-divider {
            border-color: rgba(229, 9, 20, 0.3);
            margin: 1.5rem 0;
        }

        .customer-info-card {
            background: rgba(229, 9, 20, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid rgba(229, 9, 20, 0.3);
        }

        .customer-info-card div {
            margin-bottom: 0.5rem;
        }

        /* Alert */
        .alert-success {
            background: linear-gradient(135deg, #10b981, #4ade80);
            color: var(--deep-black);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            animation: alertSlide 0.5s ease-out;
        }

        @keyframes alertSlide {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Gym-themed decorative elements */
        .gym-dumbbell {
            position: fixed;
            font-size: 3rem;
            opacity: 0.05;
            animation: floatDumbbell 20s linear infinite;
            z-index: 0;
            pointer-events: none;
        }

        .gym-dumbbell:nth-child(1) {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .gym-dumbbell:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }

        .gym-dumbbell:nth-child(3) {
            bottom: 20%;
            left: 15%;
            animation-delay: 10s;
        }

        @keyframes floatDumbbell {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .orders-header h2 {
                font-size: 1.8rem;
            }

            .search-box input {
                width: 100%;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .orders-card {
                padding: 1rem;
            }
        }
    </style>

@endsection
