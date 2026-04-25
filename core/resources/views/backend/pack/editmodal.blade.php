<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1"
     aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- Modal Header -->
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-semibold"
                    id="editModalLabel{{ $product->id }}">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Product
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Form: Use update route -->
            <form action="{{ url('admin/product/update/'.$product->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body px-3 py-3">
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
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ $product->name }}"
                                       required>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tags me-1 text-secondary"></i>
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id"
                                    class="form-select"
                                    required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
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
                                <input type="number"
                                       class="form-control"
                                       name="base_price"
                                       value="{{ $product->base_price }}"
                                       required>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-1 text-secondary"></i>
                                Product Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">৳</span>
                                <input type="number"
                                       class="form-control"
                                       name="price"
                                       value="{{ $product->price }}"
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
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-percent"></i>
                                </span>
                                <input type="number"
                                       class="form-control"
                                       name="discount"
                                       value="{{ $product->discount ?? 0 }}"
                                       min="0" max="100">
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
                                <input type="number"
                                       class="form-control"
                                       name="stock"
                                       value="{{ $product->stock }}"
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
                                      rows="4">{{ $product->details }}</textarea>
                        </div>

                        <!-- Image -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image me-1 text-secondary"></i>
                                Product Image
                            </label>
                            <input type="file"
                                   class="form-control"
                                   id="editImage{{ $product->id }}"
                                   name="image"
                                   accept="image/*">

                            <!-- Current Image -->
                            @if($product->image)
                                <div class="mt-2">
                                    <small class="text-muted">Current Image</small><br>
                                    <img src="{{ config('app.storage_url') }}{{ $product->image }}"
                                         class="img-thumbnail rounded shadow-sm mt-1"
                                         style="max-height:120px;">
                                </div>
                            @endif

                            <!-- New Image Preview -->
                            <div class="mt-2">
                                <img id="editPreview{{ $product->id }}"
                                     class="img-fluid rounded shadow-sm d-none"
                                     style="max-height:120px;">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-3 pb-3 flex-wrap">
                    <button type="button"
                            class="btn btn-outline-secondary rounded-pill px-4 mb-2"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary rounded-pill px-4 mb-2">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
document.getElementById('editImage{{ $product->id }}')
    .addEventListener('change', function(e) {
        const preview = document.getElementById('editPreview{{ $product->id }}');
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4f46e5, #6366f1);
}

.form-control:focus,
.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99,102,241,0.25);
}

.btn {
    transition: 0.25s ease-in-out;
}

.btn-primary:hover {
    background: #4f46e5;
    border-color: #4f46e5;
}

/* Modal Responsive */
@media (max-width: 768px) {
    .modal-dialog {
        max-width: 95%;
        margin: 1.75rem auto;
    }
    .modal-footer {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>