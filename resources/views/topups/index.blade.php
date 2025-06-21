@extends('layouts.app')

@section('title', 'Top-up Management - ZOTA Admin')
@section('page-title', 'Top-up Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Top-up Requests
                        </h5>
                    </div>
                    <div class="col-auto">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-modern">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topups as $topup)
                            <tr>
                                <td class="fw-bold">{{ $topup->request_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <small class="text-white fw-bold">{{ substr($topup->user->name, 0, 1) }}</small>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $topup->user->name }}</h6>
                                            <small class="text-muted">{{ $topup->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($topup->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($topup->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $topup->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('topups.show', $topup->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($topup->status === 'pending')
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $topup->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $topup->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $topup->id }}" tabindex="-1">
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
                            <div class="modal fade" id="rejectModal{{ $topup->id }}" tabindex="-1">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $topups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection