@extends('backend.app')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h4 class="mb-4 fw-bold text-center">Manage Sliders</h4>

        <form action="/admin/sliders/store" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">

                <!-- Slider 1 -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block mb-2">
                        <i class="bi bi-image me-1 text-primary"></i> Slider Image 1
                    </label>
                    <input type="file"
                           accept="image/*"
                           class="form-control form-control-lg shadow-sm"
                           name="slider1"
                           id="slider1"
                           onchange="previewImage(event, 'preview1')"
                    >
                    <div class="mt-3 d-flex justify-content-center">
                        <img id="preview1"
                             src="{{ $slider && $slider->slider1 ? config('app.storage_url').$slider->slider1 : '' }}"
                             class="img-fluid rounded-4 shadow border {{ $slider && $slider->slider1 ? '' : 'd-none' }}"
                             style="max-height: 180px; transition: all 0.3s ease;">
                    </div>
                </div>

                <!-- Slider 2 -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold d-block mb-2">
                        <i class="bi bi-image me-1 text-success"></i> Slider Image 2
                    </label>
                    <input type="file"
                           accept="image/*"
                           class="form-control form-control-lg shadow-sm"
                           name="slider2"
                           id="slider2"
                           onchange="previewImage(event, 'preview2')"
                    >
                    <div class="mt-3 d-flex justify-content-center">
                        <img id="preview2"
                             src="{{ $slider && $slider->slider2 ? config('app.storage_url').$slider->slider2 : '' }}"
                             class="img-fluid rounded-4 shadow border {{ $slider && $slider->slider2 ? '' : 'd-none' }}"
                             style="max-height: 180px; transition: all 0.3s ease;">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 mt-4 text-center">
                    <button type="submit" class="btn btn-gradient px-5 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Save Sliders
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Preview Script -->
<script>
function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    const file = event.target.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
        preview.classList.add('fade-in');
    }
}
</script>

<!-- Styles -->
<style>
/* Gradient button */
.btn-gradient {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
}
.btn-gradient:hover {
    transform: translateY(-3px);
    opacity: 0.9;
}

/* Image fade-in */
.fade-in {
    animation: fadeIn 0.5s ease-in-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

/* Form input */
.form-control-lg {
    border-radius: 12px;
    padding: 0.75rem 1rem;
}

/* Shadow for card & images */
.card, .img-fluid {
    transition: all 0.3s ease;
}
.img-fluid:hover {
    transform: scale(1.03);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .img-fluid { max-height: 150px !important; }
}
@media (max-width: 576px) {
    .img-fluid { max-height: 120px !important; }
}
</style>

@endsection