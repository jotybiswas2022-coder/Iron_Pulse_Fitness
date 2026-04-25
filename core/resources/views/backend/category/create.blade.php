@extends('backend.app')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row px-3 pt-3 pb-2 align-items-center">
        <!-- Page Title & Subtitle -->
        <div class="col-md-8 col-12">
            <h3 class="fw-bold mb-1 d-flex align-items-center flex-wrap">
                <i class="bi bi-folder-plus me-2 text-primary fs-3"></i>
                <span>Add New Category</span>
            </h3>
            <small class="text-muted d-block">
                Fill the form below to create a new category
            </small>
        </div>

        <!-- Go Back Button -->
        <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
            <a href="{{ url('admin/category') }}"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Go Back
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm mx-3 mt-3 category-card">
        <div class="card-body p-4">

            <form action="{{ url('admin/category/store') }}" method="post">
                @csrf
                <div class="row g-3">

                    <!-- Category Name -->
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-semibold">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-tag"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   placeholder="Enter category name"
                                   required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-2">
                        <button type="submit"
                                class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-save me-1"></i> Save Category
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>

<style>
.category-card {
    border-radius: 14px;
}

.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99,102,241,0.25);
}

.btn-success {
    transition: 0.25s ease-in-out;
}

.btn-success:hover {
    background: #4f46e5;
    border-color: #4f46e5;
}

@media (max-width: 768px) {
    .card-body {
        padding: 20px;
    }
}
</style>

@endsection