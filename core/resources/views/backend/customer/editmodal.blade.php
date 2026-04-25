<!-- Edit Customer Modal -->
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- Modal Header -->
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-semibold" id="editModalLabel{{ $user->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Form -->
            <form action="{{ url('admin/customers/update/'.$user->id) }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body px-4 py-3">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name{{ $user->id }}" class="form-label fw-medium">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name{{ $user->id }}" value="{{ $user->name }}" required autocomplete="off">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email{{ $user->id }}" class="form-label fw-medium">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email{{ $user->id }}" value="{{ $user->email }}" required autocomplete="off">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password{{ $user->id }}" class="form-label fw-medium">Password</label>
                        <input type="password" class="form-control" name="password" id="password{{ $user->id }}" placeholder="Enter new password if you want to change" autocomplete="new-password">
                        <small class="text-muted">Leave blank if you don't want to change the password</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation{{ $user->id }}" class="form-label fw-medium">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation{{ $user->id }}" placeholder="Confirm new password" autocomplete="new-password">
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-pencil-square me-1"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
.modal-body .form-label.fw-medium { font-weight: 500; }
.bg-gradient-primary {
    background: linear-gradient(45deg, #4f46e5, #6366f1);
}
.btn-close-white {
    filter: invert(1);
}
</style>