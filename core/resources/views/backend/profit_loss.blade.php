@extends('backend.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding: 20px 0;">

    <!-- Header -->
    <div class="contact-header mx-3 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Profit & Loss</h4>
                <p class="text-muted small mb-0">View product sales and profits over time</p>
            </div>

            <div class="mt-2 mt-md-0">
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-database me-1"></i>
                    {{ $orders->sum(fn($o) => $o->orderdetails->count()) }} Sells
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="mx-3 mb-3">
        <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="start_date" class="form-label fw-semibold">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-auto">
                <label for="end_date" class="form-label fw-semibold">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-auto d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    @php
        use App\Models\Product;
        $ser = 1;
        $totalProfit = 0;
    @endphp

    <div class="card mx-3 shadow-sm border-0 contact-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 contact-table">
                    <thead>
                        <tr class="text-center text-white" style="background:#0d6efd;">
                            <th style="width:50px;">#</th>
                            <th class="text-center">Product</th>
                            <th class="text-end">Buy Price</th>
                            <th class="text-end">Sell Price</th>
                            <th class="text-end">Profit</th>
                            <th>Sell Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
                            @foreach ($order->orderdetails as $item)
                                @php
                                    $sellDate = $item->created_at->toDateString();
                                    $startDate = request('start_date');
                                    $endDate = request('end_date');

                                    if (($startDate && $sellDate < $startDate) || ($endDate && $sellDate > $endDate)) continue;

                                    $product = Product::find($item->product_id);
                                    $discount = $product->discount ?? 0;
                                    $buyPrice = buyprice($item->product_id);
                                    $sellprice = $item->product_price * (100 - $discount) / 100;
                                    $profit = $sellprice - $buyPrice;
                                    $totalProfit += $profit;
                                @endphp

                                <tr class="text-center align-middle">
                                    <td class="fw-semibold text-muted">{{ $ser++ }}</td>
                                    <td class="text-center fw-semibold">{{ $item->product_name }}</td>
                                    <td class="text-end">{{ number_format($buyPrice, 2) }}</td>
                                    <td class="text-end">{{ number_format($sellprice, 2) }}</td>
                                    <td class="text-end fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($profit, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge date-badge">
                                            {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Dhaka')->format('d M, Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 empty-state">
                                    <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                                    <div class="fw-semibold">No Data Found</div>
                                    <small class="text-muted">Sales and profit data will appear here once available.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-light text-center">
                        <tr>
                            <td class="fw-bold text-end" colspan="4">Total Profit</td>
                            <td class="fw-bold text-success text-end">{{ number_format($totalProfit, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
/* Card Styling */
.contact-card {
    border-radius:16px;
    overflow:hidden;
    border:1px solid #e2e8f0;
    background:#ffffff;
}
.contact-table thead {
    position: sticky;
    top:0;
    z-index:5;
}
.table-hover tbody tr:hover {
    background: rgba(13,110,253,.05);
    transition: .2s ease;
}
.table th, .table td {
    vertical-align: middle;
    padding:10px 12px;
}

/* Badges */
.date-badge, .time-badge {
    background:#f1f5f9;
    color:#334155;
    border-radius:6px;
    font-weight:500;
    margin-right:4px;
    padding:3px 6px;
}

/* Profit colors */
.text-success { color: #198754 !important; }
.text-danger { color: #dc3545 !important; }

/* Empty state */
.empty-state { opacity:.8; }

/* Alerts */
.alert { border-radius:12px; font-size:14px; }

/* Filter Form Styling */
form.row.g-2 label { font-weight:500; }
form.row.g-2 input { min-width:150px; }
.btn-outline-secondary { color:#6c757d; border-color:#6c757d; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive { overflow-x: auto; }
    .contact-table td, .contact-table th { font-size:13px; padding:8px 6px; }
    .badge { font-size:11px; padding:2px 5px; }
}
</style>

@endsection