@extends('layouts.app')

@section('title', 'Transactions - ZOTA Admin')
@section('page-title', 'Transaction Monitoring')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt me-2 text-primary"></i>All Transactions
                        </h5>
                    </div>
                    <div class="col-auto">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="topup" {{ request('type') === 'topup' ? 'selected' : '' }}>Top-up</option>
                            </select>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td class="fw-bold">{{ $transaction->transaction_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <small class="text-white fw-bold">{{ substr($transaction->user->name, 0, 1) }}</small>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $transaction->user->name }}</h6>
                                            <small class="text-muted">{{ $transaction->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($transaction->type === 'purchase')
                                        <span class="badge bg-success">Purchase</span>
                                        @if($transaction->game)
                                            <br><small class="text-muted">{{ $transaction->game->name }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-info">Top-up</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($transaction->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection