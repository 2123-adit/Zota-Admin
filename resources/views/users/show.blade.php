@extends('layouts.app')

@section('title', 'User Details - ZOTA Admin')
@section('page-title', 'User Details')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>User Information
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <h3 class="text-white mb-0">{{ substr($user->name, 0, 1) }}</h3>
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h5 class="text-success mb-0">Rp {{ number_format($user->balance, 0, ',', '.') }}</h5>
                        <small class="text-muted">Current Balance</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info mb-0">{{ $user->userGames->count() }}</h5>
                        <small class="text-muted">Games Owned</small>
                    </div>
                </div>

                <div class="mt-4">
                    @if($user->is_active)
                        <span class="badge bg-success px-3 py-2">Active Account</span>
                    @else
                        <span class="badge bg-danger px-3 py-2">Suspended Account</span>
                    @endif
                </div>

                <!-- Balance Adjustment Form -->
                <div class="mt-4">
                    <button class="btn btn-primary btn-modern" data-bs-toggle="modal" data-bs-target="#adjustBalanceModal">
                        <i class="fas fa-wallet me-2"></i>Adjust Balance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>Recent Transactions
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->transactions->take(10) as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->type === 'purchase')
                                        <span class="badge bg-success">Purchase</span>
                                        <br><small class="text-muted">{{ $transaction->game->name ?? 'N/A' }}</small>
                                    @else
                                        <span class="badge bg-info">Top-up</span>
                                    @endif
                                </td>
                                <td class="fw-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Balance Adjustment Modal -->
<div class="modal fade" id="adjustBalanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('users.adjust-balance', $user->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Adjust User Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Balance</label>
                        <input type="text" class="form-control" value="Rp {{ number_format($user->balance, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select name="type" class="form-select" required>
                            <option value="add">Add Balance</option>
                            <option value="subtract">Subtract Balance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Adjust Balance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection