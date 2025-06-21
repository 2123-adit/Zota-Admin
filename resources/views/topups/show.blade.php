@extends('layouts.app')

@section('title', 'Top-up Details - ZOTA Admin')
@section('page-title', 'Top-up Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2 text-primary"></i>Top-up Request Details
                    </h5>
                    <div>
                        @if($topup->status === 'pending')
                            <span class="badge bg-warning px-3 py-2">Pending</span>
                        @elseif($topup->status === 'approved')
                            <span class="badge bg-success px-3 py-2">Approved</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">Rejected</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Request Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Request ID:</td>
                                    <td class="fw-bold">{{ $topup->request_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Amount:</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($topup->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($topup->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Requested At:</td>
                                    <td>{{ $topup->created_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                @if($topup->processed_at)
                                <tr>
                                    <td class="text-muted">Processed At:</td>
                                    <td>{{ $topup->processed_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>User Information</h6>
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <h4 class="text-white mb-0">{{ substr($topup->user->name, 0, 1) }}</h4>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $topup->user->name }}</h5>
                                    <p class="text-muted mb-1">{{ $topup->user->email }}</p>
                                    <small class="text-muted">Current Balance: <span class="fw-bold text-success">Rp {{ number_format($topup->user->balance, 0, ',', '.') }}</span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($topup->notes)
                <div class="mb-4">
                    <h6>Notes</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $topup->notes }}
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    @if($topup->status === 'pending')
                        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                        <button class="btn btn-danger btn-modern" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                    @endif
                    <a href="{{ route('topups.index') }}" class="btn btn-secondary btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Back to Top-ups
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('topups.approve', $topup->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Top-up Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this top-up request for <strong>Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('topups.reject', $topup->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Top-up Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this top-up request for <strong>Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="notes" class="form-control" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
