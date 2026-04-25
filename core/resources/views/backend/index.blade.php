@extends('backend.app')

@section('content')

@php
    use App\Models\Setting;
    use App\Models\Order;
    use App\Models\Pack;
    use App\Models\User;

    $settings = Setting::first();
    $delivery = $settings?->delivery_charge ?? 0;
    $taxPercent = $settings?->tax_percentage ?? 0;
    $currency = $settings?->currency ?? '৳';
@endphp

<div class="container-fluid py-4">

    {{-- Dashboard Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #6a11cb, #2575fc); color: #fff;">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="fw-bold fs-4 d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2 fs-3"></i>
                        <a href="/admin" class="text-white text-decoration-none">Welcome to Admin Dashboard</a>
                    </div>
                    <div class="mt-2 mt-md-0 fw-semibold">
                        Hello, <strong>{{ auth()->user()->name }}</strong>! Here's an overview of your Shop.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Stats Cards --}}
    <div class="row mb-4 g-3">
        @php
            $stats = [
                ['title'=>'Total Users','count'=>User::count(),'icon'=>'bi-people','bg'=>'linear-gradient(135deg, #4e73df, #1cc88a)'],
                ['title'=>'Total Orders','count'=>Order::count(),'icon'=>'bi-basket','bg'=>'linear-gradient(135deg, #36b9cc, #2c9faf)'],
                ['title'=>'Packs','count'=>Pack::count(),'icon'=>'bi-box','bg'=>'linear-gradient(135deg, #f6c23e, #dda20a)'],
                ['title'=>'Revenue','count'=>$currency,'icon'=>'bi-coin','bg'=>'linear-gradient(135deg, #e74a3b, #c53030)','currency'=>$currency]
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-3 col-6">
            <div class="card shadow border-0 rounded-4 text-white card-hover" style="background: {{$stat['bg']}}">
                <div class="card-body text-center py-4">
                    <h5 class="card-title">{{$stat['title']}}</h5>
                    <h3 class="fw-bold mb-2">{{$stat['count']}}</h3>
                    @isset($stat['currency'])
                    @endisset
                    <i class="bi {{$stat['icon']}} fs-3 opacity-75"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4 g-3">
        @php
            $quickStats = [
                ['title'=>'Delivery Charge','value'=>$delivery.' '.$currency,'bg'=>'#1cc88a'],
                ['title'=>'Tax Percentage','value'=>$taxPercent.'%','bg'=>'#36b9cc'],
                ['title'=>'Currency','value'=>$currency,'bg'=>'#f6c23e','icon'=>'bi-coin'],
                ['title'=>'Total Orders','value'=>Order::count(),'bg'=>'#e74a3b']
            ];
        @endphp

        @foreach($quickStats as $qs)
        <div class="col-md-3 col-6">
            <div class="card shadow border-0 rounded-4 text-center p-3 card-hover" style="background: {{$qs['bg']}}; color:#fff;">
                <h6>{{$qs['title']}}</h6>
                <h4 class="fw-bold">
                    @isset($qs['icon'])
                        <i class="bi {{$qs['icon']}} me-1"></i>
                    @endisset
                    {{$qs['value']}}
                </h4>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- Custom Styles --}}
<style>
.card-hover {
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.card-hover:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 15px 30px rgba(0,0,0,0.25);
}
.card .card-title {
    font-size: 1rem;
    opacity: 0.9;
}
.card h3 {
    margin-top: 10px;
}
</style>

@endsection