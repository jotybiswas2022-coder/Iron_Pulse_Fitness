@extends('frontend.app')

@section('content')

{{-- Alerts --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show m-3 alert-success-custom" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show m-3 alert-danger-custom" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Product Page -->
<div class="product-page py-5">

    <!-- Pack Detail -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="product-card p-4 p-md-5">

                    <div class="row g-5 align-items-start">

                        <!-- Image -->
                        <div class="col-md-6 text-center position-relative animate-in delay-1">
                            <div class="image-wrapper position-relative power-pulse">
                                <img src="{{ $pack->image ? config('app.storage_url') . $pack->image : asset('frontend/img/product1.jpg') }}"
                                     alt="{{ $pack->name }}"
                                     class="img-fluid main-image">

                                @if($pack->discount)
                                <span class="discount-badge-3d">
                                    <i class="bi bi-lightning-charge-fill gym-icon"></i> {{ $pack->discount }}% OFF
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-md-6 animate-in delay-2">
                            <h2 class="fw-bold mt-2 product-title">
                                <i class="bi bi-award-fill gym-icon"></i> {{ $pack->name }}
                            </h2>

                            <!-- Price -->
                            <div class="price-box my-4">
                                @if($pack->discount)
                                    <div>
                                        <span class="original-price">
                                            <i class="bi bi-currency-dollar"></i> 
                                            {{ number_format($pack->pack_price, 2) }} {{ currency() }}
                                        </span>
                                    </div>
                                    <h4 class="discounted-price">
                                        <i class="bi bi-cash-stack"></i> 
                                        {{ number_format($pack->pack_price * (100 - $pack->discount)/100, 2) }} {{ currency() }}
                                    </h4>
                                @else
                                    <h4 class="discounted-price">
                                        <i class="bi bi-cash-stack"></i> 
                                        {{ number_format($pack->pack_price, 2) }} {{ currency() }}
                                    </h4>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="description-box mb-4">
                                {!! $pack->details ?? '<p class="mb-0 text-muted">No details available.</p>' !!}
                            </div>

                            <!-- Buttons -->
                            @if (IsAddedToCart(auth()->id(), $pack->id))
                                <button class="btn btn-dark-theme btn-lg w-100">
                                    <i class="bi bi-cart-check me-2"></i> Already Added
                                </button>
                            @else
                                <a href="{{ url('/add_cart/'.$pack->id) }}" class="btn btn-dark-theme btn-lg w-100">
                                    <i class="bi bi-cart-plus me-2"></i> Purchase Pack
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Packs -->
    <div class="container mt-5 pt-3 animate-in delay-3">
        <h3 class="section-title">
            <i class="bi bi-fire gym-icon"></i> Related Packs
        </h3>

        @if($otherPacks->where('id', '!=', $pack->id)->count() > 0)
        <div class="swiper mySwiper mt-3">
            <div class="swiper-wrapper">

                @foreach($otherPacks as $item)
                @if($item->id != $pack->id)
                <div class="swiper-slide">
                    <div class="related-card rounded-3 text-center p-3">
                        <a href="{{ url('/pack/'.$item->id) }}" class="text-decoration-none text-light">
                            <div class="related-img-wrapper position-relative mb-2">
                                <img src="{{ config('app.storage_url') }}{{ $item->image }}"
                                     class="img-fluid related-img">

                                @if($item->discount)
                                <span class="discount-badge-small">
                                    <i class="bi bi-lightning-fill"></i> {{ $item->discount }}% OFF
                                </span>
                                @endif
                            </div>

                            <h6 class="fw-semibold mb-1">
                                {{ $item->name }}
                            </h6>
                        </a>

                        <div class="mb-2">
                            @if($item->discount)
                                <span class="old-price small">
                                    {{ number_format($item->pack_price,2) }} {{ currency() }}
                                </span> 
                                <span class="new-price">
                                    {{ number_format($item->pack_price * (100 - $item->discount)/100,2) }} {{ currency() }}
                                </span>
                            @else
                                <span class="new-price">
                                    {{ number_format($item->pack_price,2) }} {{ currency() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        @endif

    </div>

</div>

@endsection
