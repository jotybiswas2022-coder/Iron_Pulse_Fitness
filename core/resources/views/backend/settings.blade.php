@extends('backend.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding: 20px 0;">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-gear-fill me-2"></i> Settings
                    </h4>
                </div>

                <div class="card-body">

                    <form action="{{ url('admin/settings') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="currency" class="form-label fw-semibold">Currency</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="BDT" {{ $settings?->currency == 'BDT' ? 'selected' : '' }}>BDT</option>
                                <option value="USD" {{ $settings?->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ $settings?->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="INR" {{ $settings?->currency == 'INR' ? 'selected' : '' }}>INR</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="language" class="form-label fw-semibold">Language</label>
                            <select name="language" id="language" class="form-select">
                                <option value="en" {{ $settings?->language == 'en' ? 'selected' : '' }}>English</option>
                                <option value="bn" {{ $settings?->language == 'bn' ? 'selected' : '' }}>Bangla</option>
                                <option value="ar" {{ $settings?->language == 'ar' ? 'selected' : '' }}>Arabic</option>
                                <option value="fr" {{ $settings?->language == 'fr' ? 'selected' : '' }}>French</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="delivery_charge" class="form-label fw-semibold">Delivery Charge</label>
                            <input type="number" step="1" name="delivery_charge" id="delivery_charge"
                                   class="form-select" value="{{ $settings?->delivery_charge ?? 0 }}"
                                   placeholder="Enter delivery charge">
                        </div>

                        <div class="mb-4">
                            <label for="tax_percentage" class="form-label fw-semibold">Tax Percentage (%)</label>
                            <input type="number" step="0.1" name="tax_percentage" id="tax_percentage"
                                   class="form-select" value="{{ $settings?->tax_percentage ?? 0 }}"
                                   placeholder="Enter tax percentage">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-save me-1"></i> Save Settings
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<style>
.card {
    border-radius: 14px;
    overflow: hidden;
    transition: all .3s ease;
}

.card:hover {
    box-shadow: 0 12px 35px rgba(0,0,0,.15);
    transform: translateY(-2px);
}

.card-header {
    padding: 18px 24px;
    letter-spacing: .5px;
}

.form-label {
    font-size: 15px;
    color: #333;
}

.form-select {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 15px;
    border: 1px solid #ddd;
    transition: all .3s ease;
}

.form-select:focus {
    border-color: #000;
    box-shadow: 0 0 0 .15rem rgba(0,0,0,.15);
}

.btn-dark {
    border-radius: 30px;
    padding: 10px 26px;
    font-weight: 600;
    transition: all .3s ease;
}

.btn-dark:hover {
    background-color: #111;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,0,0,.25);
}

.alert {
    border-radius: 12px;
    font-size: 14px;
}
</style>

@endsection