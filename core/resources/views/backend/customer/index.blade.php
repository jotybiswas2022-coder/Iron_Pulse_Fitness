@extends('backend.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding: 20px 0;">

    <div class="row m-3 align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold">Member List</h2>
            <p class="text-muted mb-0">Manage all your members  efficiently</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/customers/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create Account
            </a>
        </div>
    </div>

    <div class="card mx-3 shadow-sm border-0 rounded-3">
        <div class="card-body p-2">

            <div class="mb-2">
                <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Search by name, email, or record time...">
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th class="text-start">Name</th>
                            <th>Email</th>
                            <th>Record Time</th>
                            <th style="width:150px;">Admin Status</th>
                            <th style="width:250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start fw-medium">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="record-time">{{ $user->created_at->format('d M, Y H:i') }}</td>

                                <td>
                                    @if($user->is_admin == 0)
                                        <a href="{{ url('admin/customers/make-admin/'.$user->id) }}" class="btn btn-sm btn-success w-100">
                                            <i class="bi bi-shield-lock"></i> Make Admin
                                        </a>
                                    @else
                                        <a href="{{ url('admin/customers/make-user/'.$user->id) }}" class="btn btn-sm btn-warning w-100">
                                            <i class="bi bi-person"></i> Make User Again
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->id }})">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            @include('backend.customer.editmodal', ['user' => $user])

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
.table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); transition: 0.2s; }
.card-body { border-radius: 12px; padding: 0.5rem !important; }
.alert { border-radius: 12px; font-size: 14px; }
.text-start.fw-medium { font-weight: 500; }
table th, table td { vertical-align: middle; }
.record-time { font-size: 0.75rem; color: #555; }
.btn-sm i { margin-right: 4px; }
</style>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('#userSearch').on('keyup', function(){
        let value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function(){
            $(this).toggle(
                $(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1 ||
                $(this).find('td:nth-child(3)').text().toLowerCase().indexOf(value) > -1 ||
                $(this).find('td:nth-child(4)').text().toLowerCase().indexOf(value) > -1
            );
        });
    });

    $('a.btn-success').on('click', function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Make this user an Admin?',
            text: "This user will get admin privileges!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, make admin'
        }).then((result) => {
            if(result.isConfirmed){ window.location.href = url; }
        });
    });

    $('a.btn-warning').on('click', function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Revert this Admin to User?',
            text: "This user will lose admin privileges!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, make user'
        }).then((result) => {
            if(result.isConfirmed){ window.location.href = url; }
        });
    });
});

function deleteUser(id) {
    Swal.fire({
        title: 'Delete this customer?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = '/admin/customers/delete/' + id;
        }
    });
}
</script>
@endsection