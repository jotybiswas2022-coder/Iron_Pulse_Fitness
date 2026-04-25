@extends('backend.app')

@section('content')

@php
    use App\Models\Setting;

    $settings = Setting::first();

    $currency = $settings?->currency ?? '৳';
@endphp

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding-bottom: 20px;">

    <!-- Header -->
    <div class="row m-3 align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1">Pack List</h2>
            <small class="text-muted">Manage all your packs efficiently</small>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/pack/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Pack
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="card mx-3 shadow-sm border-0 rounded-3">
        <div class="card-body p-2">

            <!-- Search -->
            <div class="mb-3">
                <input type="text" id="packSearch" class="form-control" placeholder="Search packs by name or category...">
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-start">Name</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Total Cost</th>
                            <th>Pack Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($packs as $pack)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="text-start fw-medium">
                                    {{ $pack->name }}
                                </td>

                                <td>
                                    {{ $pack->PackCategory->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark text-capitalize">
                                        {{ $pack->type }}
                                    </span>
                                </td>

                                <td>
                                    {{ $pack->discount ?? 0 }}%
                                </td>

                                <td>
                                    {{ number_format($pack->total_cost, 2) }} {{ $currency }}
                                </td>

                                <td>
                                    {{ number_format($pack->pack_price, 2) }} {{ $currency }}
                                </td>

                                <td>
                                    @if($pack->image)
                                        <img src="{{ config('app.storage_url') }}{{ $pack->image }}"
                                             alt="{{ $pack->name }}"
                                             class="img-thumbnail"
                                             style="width:50px; height:50px; object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $pack->id }}">
                                            Edit
                                        </button>

                                        @include('backend.pack.editmodal')

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger"
                                                onclick="confirmation({{ $pack->id }})">
                                            Delete
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No packs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- Delete Script -->
<script>
function confirmation(id) {
    Swal.fire({
        title: 'Delete Pack',
        text: 'Are you sure you want to delete this pack?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/pack/delete/' + id;
        }
    });
}

// Search
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('packSearch');

    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const category = row.children[2].textContent.toLowerCase();

            row.style.display =
                name.includes(filter) || category.includes(filter)
                ? ''
                : 'none';
        });
    });
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.card-body {
    border-radius: 12px;
}

.fw-medium {
    font-weight: 500;
}
</style>

@endsection