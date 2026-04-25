@extends('backend.app')

@section('content')

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding-bottom: 20px;">

    <!-- Header -->
    <div class="row px-3 pt-3 pb-2 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1">
                <i class="bi bi-plus-circle me-2 text-primary"></i> Add New Product
            </h3>
            <small class="text-muted">Fill the form below to add a new product</small>
        </div>

        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <a href="{{ url('admin/product') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Go Back
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm mx-3 mt-3 product-card">
        <div class="card-body p-4">

            <form action="/admin/product/store" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-box-seam me-1 text-secondary"></i>
                            Product Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-box"></i>
                            </span>
                            <input type="text" class="form-control"
                                   name="name"
                                   placeholder="Enter product name"
                                   required>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-tags me-1 text-secondary"></i>
                            Category <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Base Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-cash-coin me-1 text-secondary"></i>
                            Product Base Price <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">৳</span>
                            <input type="number" class="form-control"
                                   name="base_price"
                                   placeholder="Enter product base price"
                                   required>
                        </div>
                    </div>

                    <!-- Sell Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-currency-dollar me-1 text-secondary"></i>
                            Product Sell Price <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">৳</span>
                            <input type="number" class="form-control"
                                   name="price"
                                   placeholder="Enter product price"
                                   required>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-percent me-1 text-secondary"></i>
                            Discount (%)
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   class="form-control"
                                   name="discount"
                                   placeholder="Enter discount percentage"
                                   min="0" max="100">
                            <span class="input-group-text bg-light">%</span>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-boxes me-1 text-secondary"></i>
                            Product Stock <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-hash"></i>
                            </span>
                            <input type="number" class="form-control"
                                   name="stock"
                                   placeholder="Enter product stock"
                                   required>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-card-text me-1 text-secondary"></i>
                            Product Details
                        </label>
                        <textarea class="form-control editor"
                                  name="details"
                                  rows="5"
                                  placeholder="Enter product details"></textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-secondary"></i>
                            Product Image <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               accept="image/*"
                               class="form-control"
                               name="image"
                               id="image"
                               required>

                        <div class="mt-3">
                            <img id="preview"
                                 src=""
                                 class="img-fluid rounded shadow-sm d-none"
                                 style="max-height: 150px;">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-success px-4 rounded-pill w-100 w-md-auto">
                            <i class="bi bi-check-circle me-1"></i> Save Product
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});
</script>

<style>
.product-card {
    border-radius: 14px;
}

/* Input focus */
.form-control:focus,
.form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.15rem rgba(79,70,229,0.25);
}

/* Button hover */
.btn-success {
    transition: .25s;
}
.btn-success:hover {
    background: #4f46e5;
    border-color: #4f46e5;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .card-body { padding: 20px; }
}

@media (max-width: 576px) {
    .row.g-3 > [class*='col-'] { width: 100%; }
    .btn-success { width: 100%; }
}
</style>

@endsection