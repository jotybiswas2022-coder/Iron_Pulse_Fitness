<div class="modal fade"
     id="editModal{{ $category->id }}"
     tabindex="-1"
     aria-labelledby="editModalLabel{{ $category->id }}"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- Header -->
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-semibold"
                    id="editModalLabel{{ $category->id }}">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Category
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Form -->
            <form action="{{ url('admin/category/update/'.$category->id) }}"
                  method="POST">
                @csrf

                <div class="modal-body px-4 py-3">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-tag"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   id="name{{ $category->id }}"
                                   name="name"
                                   value="{{ $category->name }}"
                                   required>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4">

                    <button type="button"
                            class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i>
                        Save Changes
                    </button>

                </div>
            </form>

        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4f46e5, #6366f1);
}

.form-control:focus {
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
</style>