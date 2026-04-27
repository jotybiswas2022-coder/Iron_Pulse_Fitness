@extends('frontend.app')

@section('content')
@php
use App\Models\Setting;

$settings = Setting::first();
$currency = $settings?->currency ?? '&#2547;';
@endphp

@if (session('success'))
    <div class="alert alert-success m-3">{{ session('success') }}</div>
@endif

<div class="container py-4">
    <div class="orders-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">My Ordered Packs</h2>
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
                <tbody>
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

                                <td data-label="Pack Name" class="product-name">{{ $packName }}</td>

                                <td data-label="Price" class="price-cell">
                                    {{ $currency }} {{ number_format($packPrice, 2) }}
                                </td>

                                <td data-label="Remaining Time">
                                    @if($endTime)
                                        <span class="countdown" data-end="{{ $endTime->toIso8601String() }}">Loading...</span>
                                    @else
                                        <span class="text-muted">Not started</span>
                                    @endif
                                </td>

                                <td data-label="Payment Method">
                                    <span class="method-badge {{ $methodClass }}">{{ $methodLabel }}</span>
                                </td>

                                <td data-label="Status">
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                                </td>

                                <td data-label="Action">
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

<div class="modal fade" id="orderdetailsmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content order-modal">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex gap-4 flex-wrap align-items-start">
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
        document.getElementById('modalCustomer').innerText = this.dataset.firstname + ' ' + this.dataset.lastname;
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
        if (Number.isNaN(endTime)) {
            el.innerHTML = '<span class="text-danger">Invalid date</span>';
            return;
        }

        const tick = () => {
            const diff = endTime - Date.now();
            if (diff <= 0) {
                el.innerHTML = '<span class="text-danger">Expired</span>';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            el.textContent = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
        };

        tick();
        setInterval(tick, 1000);
    });
}

startCountdown();
</script>

<style>
    .orders-header {
        background: #0f172a;
        border: 1px solid rgba(59, 130, 246, 0.12);
        border-radius: 14px;
        padding: 16px 18px;
    }
    .subtitle { color: #94a3b8; font-size: 0.88rem; }
    .search-box { position: relative; min-width: 260px; }
    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }.search-box input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: 10px;
        border: 1px solid rgba(59, 130, 246, 0.25);
        background: #111827;
        color: #fff;
    }
    .orders-card {
        border-radius: 16px;
        overflow: hidden;
        background: #1e293b;
        border: 1px solid rgba(59, 130, 246, 0.12);
    }
    .product-name { font-weight: 600; color: #f1f5f9 !important; }
    .price-cell { color: #3b82f6 !important; font-weight: 600; }
    .sl-number {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .status-badge, .method-badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
    }
    .status-pending { background: #facc15; color: #111827; }
    .status-processing { background: #3b82f6; color: #fff; }
    .status-approved { background: #22c55e; color: #fff; }
    .status-cancelled { background: #ef4444; color: #fff; }
    .method-cod { background: rgba(16, 185, 129, 0.2); color: #86efac; }
    .method-bkash { background: rgba(236, 72, 153, 0.2); color: #f9a8d4; }
    .method-nagad { background: rgba(245, 158, 11, 0.2); color: #fcd34d; }
    .view-order-btn {
        border: 0;
        border-radius: 8px;
        background: #2563eb;
        color: #fff;
        padding: 6px 10px;
    }
    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #cbd5e1;
    }
    .empty-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .order-modal {
        background: #0f172a;
        color: #e2e8f0;
        border: 1px solid rgba(59, 130, 246, 0.18);
    }
    .modal-info-label { color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; }
    .customer-info-card {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 10px;
        padding: 12px;
    }
    @media (max-width: 768px) {
        .search-box { width: 100%; min-width: 100%; }
    }
</style>
@endsection