@extends('backend.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding: 20px 0;">

    <!-- Header -->
    <div class="row px-3 pt-3 pb-2 align-items-center">
        <!-- Page Title & Subtitle -->
        <div class="col-md-8 col-12 mb-2 mb-md-0">
            <h2 class="fw-bold d-flex align-items-center flex-wrap mb-1">
                <i class="bi bi-tags me-2 text-primary fs-3"></i>
                <span>Category List</span>
            </h2>
            <small class="text-muted d-block">Manage all categories efficiently</small>
        </div>

        <!-- Add New Category Button -->
        <div class="col-md-4 col-12 text-md-end">
            <a href="{{ url('admin/category/create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle me-1"></i> Add New Category
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="card mx-3 shadow-sm border-0 rounded-3 mt-3">
        <div class="card-body p-3">

            <!-- Search -->
            <div class="mb-3">
                <input type="text" id="categorySearch" class="form-control" placeholder="Search categories...">
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Name</th>
                            <th style="width:180px;">Created At</th>
                            <th style="width:200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $category->name }}</td>
                                <td>{{ $category->created_at->format('d M, Y H:i') }}</td>
                                <td class="text-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary me-2 mb-1 mb-md-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                    <h5 class="modal-title fw-semibold" id="editModalLabel{{ $category->id }}">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Category
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ url('admin/category/update/'.$category->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body px-4 py-3">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4 flex-wrap">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 mb-2" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 mb-2">
                                                            <i class="bi bi-save me-1"></i> Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-danger mb-1 mb-md-0" onclick="confirmation({{ $category->id }})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No categories found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
function confirmation(id) {
    Swal.fire({
        title: 'Delete the Category',
        text: 'Are you sure you want to delete this category?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/category/delete/' + id;
        }
    });
}

// Search functionality
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('categorySearch');
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const categoryName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            row.style.display = categoryName.includes(filter) ? '' : 'none';
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
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }

    .modal-footer {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-sm {
        width: 100%;
    }
}
</style>

@endsection