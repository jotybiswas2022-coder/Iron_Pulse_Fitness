<div class="modal fade" id="editModal{{ $pack->id }}" tabindex="-1"
     aria-labelledby="editModalLabel{{ $pack->id }}" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- Header -->
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-semibold"
                    id="editModalLabel{{ $pack->id }}">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Pack
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Form -->
            <form action="{{ url('admin/pack/update/'.$pack->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body px-3 py-3">
                    <div class="row g-3">

                        <!-- Pack Name -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-box-seam me-1 text-secondary"></i>
                                Pack Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   value="{{ $pack->name }}"
                                   required>
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
                                    <option value="{{ $category->id }}"
                                        {{ $pack->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pack Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tags me-1 text-secondary"></i>
                                Pack Type <span class="text-danger">*</span>
                            </label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Pack Type</option>
                                <option value="basic" {{ $pack->type == 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="standard" {{ $pack->type == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="premium" {{ $pack->type == 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
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
                                       value="{{ $pack->discount }}"
                                       min="0" max="100">
                                <span class="input-group-text bg-light">%</span>
                            </div>
                        </div>

                        <!-- Total Cost -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-cash-coin me-1 text-secondary"></i>
                                Total Cost <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">৳</span>
                                <input type="number"
                                       class="form-control"
                                       name="total_cost"
                                       value="{{ $pack->total_cost }}"
                                       required>
                            </div>
                        </div>

                        <!-- Pack Price -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-1 text-secondary"></i>
                                Pack Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">৳</span>
                                <input type="number"
                                       class="form-control"
                                       name="pack_price"
                                       value="{{ $pack->pack_price }}"
                                       required>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text me-1 text-secondary"></i>
                                Pack Details
                            </label>
                            <textarea class="form-control editor"
                                      name="details"
                                      rows="4">{{ $pack->details }}</textarea>
                        </div>

                        <!-- Image -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image me-1 text-secondary"></i>
                                Pack Image
                            </label>

                            <input type="file"
                                   class="form-control"
                                   id="editImage{{ $pack->id }}"
                                   name="image"
                                   accept="image/*">

                            <!-- Current Image -->
                            @if($pack->image)
                                <div class="mt-2">
                                    <small class="text-muted">Current Image</small><br>
                                    <img src="{{ config('app.storage_url') }}{{ $pack->image }}"
                                         class="img-thumbnail rounded shadow-sm mt-1"
                                         style="max-height:120px;">
                                </div>
                            @endif

                            <!-- Preview -->
                            <div class="mt-2">
                                <img id="editPreview{{ $pack->id }}"
                                     class="img-fluid rounded shadow-sm d-none"
                                     style="max-height:120px;">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-3 pb-3 flex-wrap">
                    <button type="button"
                            class="btn btn-outline-secondary rounded-pill px-4 mb-2"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-success rounded-pill px-4 mb-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview -->
<script>
document.getElementById('editImage{{ $pack->id }}')
    .addEventListener('change', function(e) {
        const preview = document.getElementById('editPreview{{ $pack->id }}');
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
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.15rem rgba(79,70,229,0.25);
}

.btn-success:hover {
    background: #4f46e5;
    border-color: #4f46e5;
}
</style>