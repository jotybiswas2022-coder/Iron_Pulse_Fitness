@extends('backend.app')

@section('content')

@php
    use App\Models\Setting;
    $settings = Setting::first();
    $currency = $settings?->currency ?? '৳';
@endphp

<div class="row m-3 align-items-center mb-3">
    <div class="col-md-6">
        <h2 class="fw-bold">Order List</h2>
        <p class="text-muted mb-0">Manage all your orders efficiently</p>
    </div>
</div>

<div class="card mx-3 shadow-sm border-0 rounded-3">
    <div class="card-body p-2">

        <!-- Search -->
        <div class="mb-2">
            <input type="text" id="orderSearch" class="form-control form-control-sm"
                   placeholder="Search by customer, phone, or record time...">
        </div>

        <div class="table-responsive table-container">
            <table class="table table-sm table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th class="text-start">Customer</th>
                    <th>Phone</th>
                    <th>Pack </th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Remaining</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($orders as $order)

                    @php
                        $status = strtolower(trim($order->status));
                        $badgeClass = match($status) {
                            'approved' => 'bg-success',
                            'pending' => 'bg-warning text-dark',
                            'canceled','cancelled' => 'bg-danger',
                            'delivered' => 'bg-primary',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <tr id="order-{{ $order->id }}">
                        <td>{{ $loop->iteration }}</td>

                        <td class="text-start fw-medium">
                            {{ $order->firstname }} {{ $order->lastname }}
                        </td>

                        <td>{{ $order->phone }}</td>

                        <td class="text-start">
                            <ul class="product-list mb-0 ps-0">
                                @foreach ($order->orderdetails as $item)
                                    <li>
                                        <span class="product-name">{{ $item->product_name }}</span>
                                        <span class="product-qty">× {{ $item->product_quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <td>{{ number_format($order->total_price,2) }} {{ $currency }}</td>

                        <!-- Payment -->
                        <td>
                            @php
                                $method = strtolower(trim($order->payment_method ?? ''));
                                $methodClass = match($method) {
                                    'cod' => 'bg-primary',
                                    'bkash' => 'bg-success',
                                    'nagad' => 'bg-warning text-dark',
                                    default => 'bg-secondary',
                                };
                                $methodText = match($method) {
                                    'cod' => 'Cash on Delivery',
                                    'bkash' => 'Bkash',
                                    'nagad' => 'Nagad',
                                    default => 'Unknown',
                                };
                            @endphp
                            <span class="badge {{ $methodClass }}">{{ $methodText }}</span>
                        </td>

                        <!-- Status -->
                        <td class="status">
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="record-time">
                            {{ $order->created_at->format('d M, Y H:i') }}
                        </td>

                        <!-- Actions -->
                        <td class="action-cell">

                            @if($status === 'pending')
                                <button class="btn btn-sm btn-primary btn-approve"
                                        data-id="{{ $order->id }}">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>

                                <button class="btn btn-sm btn-danger btn-cancel"
                                        data-id="{{ $order->id }}">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>

                            @elseif($status === 'approved')
                                <button class="btn btn-sm btn-success btn-delivered"
                                        data-id="{{ $order->id }}">
                                    <i class="bi bi-truck"></i> Order Delivered
                                </button>
                            @else
                                <span class="text-muted">No actions</span>
                            @endif

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="text-muted">No orders found.</td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<style>
.table-container {max-height: 450px; overflow-y: auto; font-size: 13px;}
.table-hover tbody tr:hover {background-color: rgba(13,110,253,0.05);}
.badge {font-size: 11px; border-radius: 10px;}
.product-list {list-style:none; font-size:12px;}
.product-list li {display:flex; justify-content:space-between;}
.record-time {font-size:12px;}
</style>

@endsection

@section('scripts')
<script>
$(function(){

    // ✅ GLOBAL CSRF (important)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    function updateStatus(row, text, colorClass){
        let badge = row.find('.status .badge');
        badge.attr('class','badge '+colorClass);
        badge.text(text);
    }

    function removeActions(row){
        row.find('.action-cell')
            .html('<span class="text-muted">No actions</span>');
    }

    // ================= APPROVE =================
    $(document).on('click','.btn-approve',function(){
        let id = $(this).data('id');

        Swal.fire({
            title:'Approve this order?',
            icon:'question',
            showCancelButton:true,
            confirmButtonColor:'#198754',
            confirmButtonText:'Yes, approve'
        }).then((result)=>{
            if(!result.isConfirmed) return;

            $.post('/admin/orders/approve/'+id)
            .done(function(res){
                let row = $('#order-'+id);

                updateStatus(row,'Approved','bg-success');

                row.find('.action-cell').html(
                    `<button class="btn btn-sm btn-success btn-delivered" data-id="${id}">
                        <i class="bi bi-truck"></i> Order Delivered
                    </button>`
                );

                Swal.fire('Approved!','','success');
            })
            .fail(function(xhr){
                Swal.fire('Error!', xhr.responseJSON?.error || 'Something went wrong','error');
            });
        });
    });

    // ================= CANCEL =================
    $(document).on('click','.btn-cancel',function(){
        let id = $(this).data('id');

        Swal.fire({
            title:'Cancel this order?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            confirmButtonText:'Yes, cancel'
        }).then((result)=>{
            if(!result.isConfirmed) return;

            $.post('/admin/orders/cancel/'+id)
            .done(function(){
                let row = $('#order-'+id);
                updateStatus(row,'Canceled','bg-danger');
                removeActions(row);
                Swal.fire('Canceled!','','success');
            })
            .fail(function(xhr){
                Swal.fire('Error!', xhr.responseJSON?.error || 'Something went wrong','error');
            });
        });
    });

    // ================= DELIVERED =================
    $(document).on('click','.btn-delivered',function(){
        let id = $(this).data('id');

        Swal.fire({
            title:'Mark as delivered?',
            icon:'question',
            showCancelButton:true,
            confirmButtonColor:'#198754',
            confirmButtonText:'Yes, delivered'
        }).then((result)=>{
            if(!result.isConfirmed) return;

            $.post('/admin/orders/delivered/'+id)
            .done(function(){
                let row = $('#order-'+id);
                updateStatus(row,'Delivered','bg-primary');
                removeActions(row);
                Swal.fire('Order Delivered!','','success');
            })
            .fail(function(xhr){
                Swal.fire('Error!', xhr.responseJSON?.error || 'Something went wrong','error');
            });
        });
    });

    // ================= SEARCH =================
    $('#orderSearch').on('keyup',function(){
        let value = $(this).val().toLowerCase();
        $('tbody tr').each(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

});
</script>
@endsection