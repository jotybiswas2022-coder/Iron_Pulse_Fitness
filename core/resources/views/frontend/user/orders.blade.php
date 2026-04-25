@extends('frontend.app')

@section('content')

@php
use App\Models\Setting;
$settings = Setting::first();
$currency = $settings?->currency ?? '৳';
$delivery = $settings->delivery_charge;
@endphp

@if (session('success'))

<div class="alert alert-custom m-3">
    <i class="bi bi-check-circle me-1"></i>
    {{ session('success') }}
</div>
@endif

<div class="container py-4">

<!-- HEADER -->
<div class="orders-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2>My Orders</h2>
        <div class="subtitle">Track and manage your recent orders</div>
    </div>

    <div class="search-box">
        <input type="text" id="orderSearch" placeholder="Search orders...">
        <i class="bi bi-search"></i>
    </div>
</div>

<!-- TABLE CARD -->
<div class="orders-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-center table-dark" id="ordersTable">
            <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Delivery Charge</th>
                <th>Payment Mrthod</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @php $sl = 1; @endphp

            @forelse($orders as $order)
                @foreach($order->orderdetails as $product)

                    @php
                        $productModel = $product->product;
                        $imageUrl = ($productModel && $productModel->image)
                            ? config('app.storage_url').$productModel->image
                            : '';

                        $status = strtolower($product->status);

                        $statusClass = match($status) {
                            'pending' => 'status-pending',
                            'processing' => 'status-processing',
                            'approve','approved','delivered' => 'status-approved',
                            'canceled','cancelled' => 'status-cancelled',
                            default => 'status-processing'
                        };

                        $method = strtolower($order->payment_method ?? '');

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
                    @endphp

                    <tr class="order-row">

                        <td data-label="#">
                            <span class="sl-number">{{ $sl++ }}</span>
                        </td>

                        <td data-label="Product">
                            @if($imageUrl)
                                <div class="product-img-wrap">
                                    <img src="{{ $imageUrl }}">
                                </div>
                            @else
                                N/A
                            @endif
                        </td>

                        <td data-label="Name" class="product-name">
                            {{ $product->product_name }}
                        </td>

                        <td data-label="Price" class="price-cell">
                            {{ $currency }} {{ number_format($product->product_price,2) }}
                        </td>

                        <td data-label="Qty">
                            <span class="qty-badge">
                                {{ $product->product_quantity }}
                            </span>
                        </td>

                        <td data-label="Total" class="total-cell">
                            {{ $currency }} {{ number_format($product->product_price * $product->product_quantity ,2) }}
                        </td>

                        <td data-label="Delivery Charge" class="price-cell">
                            {{ $currency }} {{ number_format($delivery,2) }}
                        </td>

                        <td data-label="Payment Method">
                            <span class="method-badge {{ $methodClass }}">
                                {{ $methodLabel }}
                            </span>
                        </td>

                        <td data-label="Status">
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>

                        <td data-label="Action">
                            <button class="view-order-btn"
                                data-name="{{ $product->product_name }}"
                                data-price="{{ $currency }} {{ number_format($product->product_price,2) }}"
                                data-qty="{{ $product->product_quantity }}"
                                data-total="{{ $currency }} {{ number_format($product->product_price * $product->product_quantity,2) }}"
                                data-status="{{ ucfirst($product->status) }}"
                                data-statusclass="{{ $statusClass }}"
                                data-image="{{ $imageUrl }}"
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
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-bag-x"></i>
                            </div>
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

<!-- MODAL -->

<div class="modal fade" id="orderdetailsmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content order-modal">

            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

            <div class="d-flex gap-4 flex-wrap align-items-start">

                <div class="modal-product-img">
                    <img id="modalImage">
                </div>

                <div class="flex-grow-1">

                    <h6 id="modalName" class="mb-3 fw-bold"></h6>

                    <div class="row g-3 small">

                        <div class="col-6">
                            <div class="modal-info-label">Price</div>
                            <div id="modalPrice" class="modal-info-value"></div>
                        </div>

                        <div class="col-6">
                            <div class="modal-info-label">Qty</div>
                            <div id="modalQty" class="modal-info-value"></div>
                        </div>

                        <div class="col-6">
                            <div class="modal-info-label">Total</div>
                            <div id="modalTotal" class="modal-info-value text-success fw-bold"></div>
                        </div>

                        <div class="col-6">
                            <div class="modal-info-label">Status</div>
                            <div id="modalStatus"></div>
                        </div>
                    </div>

                    <hr class="modal-divider">

                    <div class="customer-info-card small">
                        <div class="modal-info-label mb-2 text-light">Customer Info</div>
                        <div id="modalCustomer"></div>
                        <div id="modalEmail"></div>
                        <div id="modalPhone"></div>
                        <div id="modalAddress"></div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

</div>

<!-- SCRIPT -->

<script>
document.getElementById('orderSearch').addEventListener('keyup', function () {
    let v = this.value.toLowerCase();
    document.querySelectorAll('#ordersTable tbody .order-row')
        .forEach(r => r.style.display = r.innerText.toLowerCase().includes(v) ? '' : 'none');
});

document.querySelectorAll('.view-order-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        const modalName = document.getElementById('modalName');
        const modalPrice = document.getElementById('modalPrice');
        const modalQty = document.getElementById('modalQty');
        const modalTotal = document.getElementById('modalTotal');
        const modalCustomer = document.getElementById('modalCustomer');
        const modalEmail = document.getElementById('modalEmail');
        const modalPhone = document.getElementById('modalPhone');
        const modalAddress = document.getElementById('modalAddress');
        const modalStatus = document.getElementById('modalStatus');
        const modalImage = document.getElementById('modalImage');

        modalName.innerText = this.dataset.name;
        modalPrice.innerText = this.dataset.price;
        modalQty.innerText = this.dataset.qty;
        modalTotal.innerText = this.dataset.total;

        modalCustomer.innerText = this.dataset.firstname + ' ' + this.dataset.lastname;
        modalEmail.innerText = this.dataset.email;
        modalPhone.innerText = this.dataset.phone;
        modalAddress.innerText = this.dataset.address;

        modalStatus.innerHTML =
            '<span class="status-badge '+this.dataset.statusclass+'">'+this.dataset.status+'</span>';

        if (this.dataset.image) {
            modalImage.src = this.dataset.image;
            modalImage.style.display = 'block';
        } else {
            modalImage.style.display = 'none';
        }
    });
});
</script>

<style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        :root {
            --primary: #0f172a;
            --primary-deep: #070d1a;
            --card: #1e293b;
            --card-alt: #162032;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-glow: rgba(59, 130, 246, 0.25);
            --success: #22c55e;
            --warning: #facc15;
            --danger: #ef4444;
            --muted: #94a3b8;
            --border-subtle: rgba(59, 130, 246, 0.12);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-bkash: #e12edb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-deep);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.04) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.03) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(59, 130, 246, 0.02) 0%, transparent 50%);
            z-index: 0;
            animation: bgFloat 20s ease-in-out infinite;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(2%, -1%) rotate(1deg); }
            66% { transform: translate(-1%, 2%) rotate(-1deg); }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        /* ===== NAVBAR ===== */
        .top-navbar {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-navbar .brand {
            font-weight: 800;
            font-size: 1.3rem;
            background: linear-gradient(135deg, #fff 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-link-custom {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: white;
            background: rgba(59, 130, 246, 0.15);
        }

        .nav-link-custom.active {
            background: linear-gradient(135deg, var(--accent), #6366f1);
            color: white;
        }

        /* ===== ALERT ===== */
        .alert-custom {
            background: linear-gradient(135deg, var(--accent), #6366f1);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 500;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.3);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== HEADER ===== */
        .orders-header {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(15, 23, 42, 0.92) !important;
            border: 1px solid var(--border-subtle);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 18px 22px;
            position: sticky;
            top: 62px;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .orders-header h2 {
            font-weight: 900;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 30%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .orders-header .subtitle {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .orders-count {
            background: linear-gradient(135deg, var(--accent), #6366f1);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        /* ===== SEARCH ===== */
        .search-box {
            position: relative;
            min-width: 260px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border-radius: 12px;
            border: 1.5px solid rgba(59, 130, 246, 0.2);
            background: rgba(17, 24, 39, 0.8);
            color: white;
            font-size: 0.88rem;
            font-weight: 400;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-box input::placeholder {
            color: #64748b;
        }

        .search-box input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), 0 4px 20px rgba(59, 130, 246, 0.1);
            background: #111827;
        }

        .search-box input:focus + i,
        .search-box:focus-within i {
            color: var(--accent);
        }

        /* ===== STATS CARDS ===== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--card);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-subtle);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }

        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, var(--accent), #6366f1); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, var(--success), #16a34a); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, var(--warning), #eab308); }
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, var(--danger), #dc2626); }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .stat-card:nth-child(1) .stat-icon { background: rgba(59, 130, 246, 0.15); color: var(--accent); }
        .stat-card:nth-child(2) .stat-icon { background: rgba(34, 197, 94, 0.15); color: var(--success); }
        .stat-card:nth-child(3) .stat-icon { background: rgba(250, 204, 21, 0.15); color: var(--warning); }
        .stat-card:nth-child(4) .stat-icon { background: rgba(239, 68, 68, 0.15); color: var(--danger); }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* ===== TABLE CARD ===== */
        .orders-card {
            border-radius: 18px;
            overflow: hidden;
            background: var(--card);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 1px 0 rgba(255, 255, 255, 0.03) inset;
            border: 1px solid var(--border-subtle);
        }

        .orders-card table {
            table-layout: auto; 
            width: 100%;
            font-size: 0.72rem;
            margin-bottom: 0;
        }

        .orders-card thead {
            background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
        }

        .orders-card thead th {
            font-size: 0.62rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            color: var(--muted);
            padding: 10px 8px;
            border-bottom: 1px solid var(--border-subtle);
            white-space: normal;
        }

        .orders-card tbody tr {
            background: transparent;
            transition: all 0.35s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .orders-card tbody tr:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.08), rgba(99, 102, 241, 0.04), transparent);
        }

        .orders-card tbody td {
            font-size: 0.68rem;
            padding: 6px 4px;
            vertical-align: middle;
            color: var(--text-secondary);
            border: none;
            white-space: normal;
            word-break: break-word;
        }

        .orders-card tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.015);
        }

        .orders-card tbody tr:nth-child(even):hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.08), rgba(99, 102, 241, 0.04), transparent);
        }

        /* ===== PRODUCT IMAGE ===== */
        .product-img-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            overflow: hidden;
            margin: auto;
            background: linear-gradient(135deg, #334155, #1e293b);
            transition: all 0.35s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-img-wrap:hover {
            transform: scale(1.12) rotate(2deg);
            box-shadow: 0 0 20px var(--accent-glow), 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--accent);
        }

        .product-img-wrap:hover img {
            transform: scale(1.1);
        }

        /* ===== PRODUCT NAME ===== */
        .product-name {
            font-weight: 600;
            color: var(--text-primary) !important;
            font-size: 0.85rem;
            line-height: 1.3;
        }

        /* ===== PRICE ===== */
        .price-cell {
            color: var(--accent) !important;
            font-weight: 600;
            font-family: 'Inter', monospace;
        }

        .total-cell {
            color: var(--success) !important;
            font-weight: 700;
            font-family: 'Inter', monospace;
            font-size: 0.9rem;
        }

        /* ===== BADGES ===== */
        .qty-badge {
            background: linear-gradient(135deg, var(--accent), #6366f1);
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.65rem;
            color: white;
            box-shadow: 0 3px 12px rgba(59, 130, 246, 0.3);
            display: inline-block;
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: inline-block;
        }

        .method-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            display: inline-block;
        }

        /* Status colors */
        .status-pending {
            background: linear-gradient(135deg, #facc15, #eab308);
            color: #1a1a1a;
            box-shadow: 0 3px 12px rgba(250, 204, 21, 0.25);
        }

        .status-processing {
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: white;
            box-shadow: 0 3px 12px rgba(59, 130, 246, 0.3);
        }

        .status-approved, .status-delivered {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            box-shadow: 0 3px 12px rgba(34, 197, 94, 0.25);
        }

        .status-cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 3px 12px rgba(239, 68, 68, 0.25);
        }

        /* Method colors */
        .method-cod {
            background: rgba(1, 119, 52, 0.15);
            color: var(--success);
            border: 1px solid rgba(5, 129, 36, 0.3);
        }

        .method-bkash {
            background: rgba(224, 4, 245, 0.12);
            color: var(--text-bkash);
            border: 1px solid rgba(236, 5, 244, 0.3);
        }

        .method-nagad {
            background: rgba(250, 204, 21, 0.12);
            color: var(--warning);
            border: 1px solid rgba(250, 204, 21, 0.3);
        }

        /* ===== VIEW BUTTON ===== */
        .view-order-btn {
            background: linear-gradient(135deg, var(--accent), #6366f1) !important;
            border: none !important;
            border-radius: 10px;
            padding: 5px 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .view-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.45);
        }

        .view-order-btn:active {
            transform: translateY(0);
        }

        /* ===== SL NUMBER ===== */
        .sl-number {
            width: 22px;
            height: 22px;
            border-radius: 8px;
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
        }

        /* ===== MODAL ===== */
        .modal-backdrop {
            backdrop-filter: blur(5px);
        }

        .order-modal {
            background: linear-gradient(160deg, #1e293b 0%, #0f172a 100%);
            border-radius: 22px;
            border: 1px solid var(--border-subtle);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .order-modal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), #6366f1, var(--accent));
            background-size: 200%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .order-modal .modal-header {
            border: none;
            padding: 24px 28px 8px;
        }

        .order-modal .modal-title {
            font-weight: 800;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #fff, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .order-modal .modal-body {
            padding: 16px 28px 28px;
        }

        .order-modal .btn-close {
            filter: invert(1) brightness(0.7);
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .order-modal .btn-close:hover {
            opacity: 1;
        }

        .modal-product-img {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(135deg, #334155, #1e293b);
            border: 2px solid var(--border-subtle);
            flex-shrink: 0;
        }

        .modal-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-info-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-info-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .modal-divider {
            border-color: rgba(59, 130, 246, 0.1);
            margin: 16px 0;
        }

        .customer-info-card {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border-subtle);
            color: whitesmoke;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--muted);
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .order-row {
            animation: fadeInUp 0.4s ease forwards;
            opacity: 0;
        }

        .order-row:nth-child(1) { animation-delay: 0.05s; }
        .order-row:nth-child(2) { animation-delay: 0.1s; }
        .order-row:nth-child(3) { animation-delay: 0.15s; }
        .order-row:nth-child(4) { animation-delay: 0.2s; }
        .order-row:nth-child(5) { animation-delay: 0.25s; }
        .order-row:nth-child(6) { animation-delay: 0.3s; }
        .order-row:nth-child(7) { animation-delay: 0.35s; }
        .order-row:nth-child(8) { animation-delay: 0.4s; }

        /* ===== PULSE DOT ===== */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: pulse 2s ease-in-out infinite;
        }

        .pulse-dot.green { background: var(--success); }
        .pulse-dot.yellow { background: var(--warning); }
        .pulse-dot.blue { background: var(--accent); }
        .pulse-dot.red { background: var(--danger); }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .orders-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 14px;
                top: 0;
            }

            .search-box {
                width: 100%;
            }

            .orders-card table {
                font-size: 0.82rem;
            }

            .product-img-wrap {
                width: 48px;
                height: 48px;
                border-radius: 12px;
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-card .stat-value {
                font-size: 1.2rem;
            }

            .orders-card .table-responsive {
                overflow-x: hidden;
            }

            .orders-card table thead {
                display: none;
            }

            .orders-card table,
            .orders-card tbody,
            .orders-card tr,
            .orders-card td {
                display: block;
                width: 100%;
            }

            .orders-card tr.order-row {
                margin: 12px 14px;
                padding: 18px;
                border-radius: 16px;
                background: var(--card) !important;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                border: 1px solid var(--border-subtle);
                position: relative;
                overflow: hidden;
            }

            .orders-card tr.order-row::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, var(--accent), #6366f1);
            }

            .orders-card td {
                text-align: left !important;
                padding: 7px 0;
                border: none;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .orders-card td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--muted);
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                flex-shrink: 0;
                margin-right: 12px;
            }

            .orders-card td:first-child {
                padding-top: 4px;
            }

            .product-img-wrap {
                margin: 0;
                width: 60px;
                height: 60px;
            }

            .view-order-btn {
                width: 100%;
                justify-content: center;
                margin-top: 8px;
                padding: 10px !important;
            }

            .top-navbar .d-flex {
                flex-wrap: wrap;
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .orders-header h2 {
                font-size: 1.2rem;
            }

            .stat-card .stat-value {
                font-size: 1.1rem;
            }

            .modal-product-img {
                width: 90px;
                height: 90px;
            }

            .order-modal .modal-body {
                padding: 12px 18px 22px;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
        }

        /* ===== TOOLTIP ===== */
        .hover-glow:hover {
            text-shadow: 0 0 15px var(--accent-glow);
        }

        /* Table column widths */
        .orders-card table th:nth-child(1),
        .orders-card table td:nth-child(1) { width: 5%; }
        .orders-card table th:nth-child(2),
        .orders-card table td:nth-child(2) { width: 9%; }
        .orders-card table th:nth-child(3),
        .orders-card table td:nth-child(3) { width: 20%; }
        .orders-card table th:nth-child(4),
        .orders-card table td:nth-child(4) { width: 12%; }
        .orders-card table th:nth-child(5),
        .orders-card table td:nth-child(5) { width: 8%; }
        .orders-card table th:nth-child(6),
        .orders-card table td:nth-child(6) { width: 13%; }
        .orders-card table th:nth-child(7),
        .orders-card table td:nth-child(7) { width: 14%; }
        .orders-card table th:nth-child(8),
        .orders-card table td:nth-child(8) { width: 11%; }
        .orders-card table th:nth-child(9),
        .orders-card table td:nth-child(9) { width: 10%; }

        @media (max-width: 768px) {
            .orders-card table th,
            .orders-card table td {
                width: 100% !important;
            }
        }

        /* Footer */
        .page-footer {
            background: var(--card);
            border-top: 1px solid var(--border-subtle);
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
            color: var(--muted);
            font-size: 0.82rem;
        }
    </style>

@endsection
