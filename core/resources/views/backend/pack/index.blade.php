@extends('backend.app')

@section('content')

@php
    use App\Models\Setting;

    $settings = Setting::first();

    $delivery = $settings?->delivery_charge ?? 0;
    $taxPercent = $settings?->tax_percentage ?? 0;
    $currency = $settings?->currency ?? '৳';
@endphp

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding-bottom: 20px;">

    <div class="row m-3 align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1">Product List</h2>
            <small class="text-muted">Manage all your products efficiently</small>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/pack/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="card mx-3 shadow-sm border-0 rounded-3">
        <div class="card-body p-2">

            <div class="mb-3">
                <input type="text" id="productSearch" class="form-control" placeholder="Search products by name or category...">
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th class="text-start">Name</th>
                            <th>Category</th>
                            <th>Base Price</th>
                            <th>Sell Price</th>
                            <th>Discount</th>
                            <th>Stock</th>
                            <th>Image</th>
                            <th style="width:200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function confirmation(id) {
    Swal.fire({
        title: 'Delete the Product',
        text: 'Are you sure you want to delete this product?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/product/delete/' + id;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('productSearch');
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            row.style.display = (name.includes(filter) || category.includes(filter)) ? '' : 'none';
        });
    });
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transition: 0.2s;
}

.card-body {
    border-radius: 12px;
}

.btn-sm i {
    margin-right: 4px;
}

.alert {
    border-radius: 12px;
    font-size: 14px;
}

.fw-medium {
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .table-responsive { overflow-x: auto; }
    .d-flex.flex-wrap.justify-content-center { flex-direction: column; gap: 5px; }
    table th, table td { white-space: nowrap; font-size: 13px; }
    .card-body { padding: 1rem; }
}

@media (max-width: 576px) {
    table th, table td { font-size: 12px; padding: 0.35rem; }
    .btn-sm { font-size: 12px; padding: 4px 6px; }
    .btn-sm i { margin-right: 2px; }
}
</style>

@endsection