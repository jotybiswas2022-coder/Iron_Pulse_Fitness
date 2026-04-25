@extends('backend.app')

@section('content')

<div class="row m-3 align-items-center mb-3">
    <div class="col-md-6">
        <h2 class="fw-bold">Create User Account</h2>
        <p class="text-muted mb-0">Fill in the details to create a new user</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ url('admin/customers') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to User List
        </a>
    </div>
</div>

<div class="card mx-3 shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('admin/customers/store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-medium">Full Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter user name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter user email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-medium">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-plus-circle me-1"></i> Create Account
            </button>
        </form>
    </div>
</div>

<style>
.card-body {
    border-radius: 12px;
}

.btn i {
    margin-right: 4px;
}

.alert {
    border-radius: 12px;
}

.form-label.fw-medium {
    font-weight: 500;
}
</style>

@endsection