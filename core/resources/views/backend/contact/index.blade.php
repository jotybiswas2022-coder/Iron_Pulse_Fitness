@extends('backend.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid" style="height: calc(100vh - 80px); overflow-y: auto; padding: 20px 0;">

    <!-- Header -->
    <div class="contact-header mx-3 mt-3 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Contact Messages</h4>
                <p class="text-muted small mb-0">Manage customer inquiries from one place</p>
            </div>

            <div class="mt-2 mt-md-0">
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-database me-1"></i>
                    {{ $contacts->count() }} Messages
                </span>
            </div>
        </div>
    </div>

    <div class="card mx-3 shadow-sm border-0 contact-card">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 contact-table">

                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th class="text-start">
                                <i class="bi bi-person me-1 text-primary"></i> Name
                            </th>
                            <th>
                                <i class="bi bi-envelope me-1 text-primary"></i> Email
                            </th>
                            <th class="text-start">
                                <i class="bi bi-chat-dots me-1 text-primary"></i> Message
                            </th>
                            <th style="width:120px;">
                                <i class="bi bi-calendar-event me-1 text-primary"></i> Date
                            </th>
                            <th style="width:100px;">
                                <i class="bi bi-clock me-1 text-primary"></i> Time
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td class="fw-semibold text-muted">{{ $loop->iteration }}</td>

                                <td class="text-start fw-semibold">{{ $contact->name }}</td>

                                <td>
                                    <span class="text-muted small">{{ $contact->email }}</span>
                                </td>

                                <td class="text-start">
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#messageModal{{ $contact->id }}">
                                        View Message
                                    </button>

                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal{{ $contact->id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $contact->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                    <h5 class="modal-title fw-semibold" id="messageModalLabel{{ $contact->id }}">
                                                        <i class="bi bi-chat-dots me-2"></i> Message from {{ $contact->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body px-4 py-3">
                                                    <p>{{ $contact->message }}</p>
                                                </div>
                                                <div class="modal-footer border-0 px-4 pb-4">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge date-badge">
                                        {{ \Carbon\Carbon::parse($contact->created_at)->timezone('Asia/Dhaka')->format('d M Y') }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge time-badge">
                                        {{ \Carbon\Carbon::parse($contact->created_at)->timezone('Asia/Dhaka')->format('h:i A') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                                        <div class="fw-semibold">No Messages Found</div>
                                        <small class="text-muted">
                                            Customer messages will appear here once submitted.
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<style>
.contact-header{
    background:#fff;
    border-radius:14px;
    padding:18px 20px;
    box-shadow:0 2px 10px rgba(0,0,0,.04);
}

.contact-card{
    border-radius:14px;
    overflow:hidden;
}

.contact-table thead{
    background:#f8fafc;
    position:sticky;
    top:0;
    z-index:5;
}

.table-hover tbody tr:hover{
    background:rgba(13,110,253,.06);
    transition:.18s ease;
}

.message-box{
    max-width:420px;
    font-size:14px;
    color:#555;
    line-height:1.5;
    white-space:normal;
    word-break:break-word;
    max-height:120px;
    overflow-y:auto;
    padding-right:4px;
}

.date-badge, .time-badge{
    background:#f1f5f9;
    color:#334155;
    border:1px solid #e2e8f0;
    font-weight:500;
}

.empty-state{
    opacity:.9;
}

.alert{
    border-radius:12px;
    font-size:14px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
    .contact-table td {
        font-size:13px;
    }
    .contact-table th {
        font-size:13px;
    }
    .badge {
        font-size:12px;
        padding:3px 6px;
    }
    .modal-body p {
        font-size:14px;
    }
}
</style>

@endsection