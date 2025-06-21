@extends('layouts.app')

@section('title', 'Transaction Details - ZOTA Admin')
@section('page-title', 'Transaction Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-receipt me-2 text-primary"></i>Transaction Details
                    </h5>
                    <div>
                        @if($transaction->status === 'completed')
                            <span class="badge bg-success px-3 py-2">Completed</span>
                        @elseif($transaction->status === 'pending')
                            <span class="badge bg-warning px-3 py-2">Pending</span>
                        @elseif($transaction->status === 'failed')
                            <span class="badge bg-danger px-3 py-2">Failed</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">{{ ucfirst($transaction->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Transaction Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Transaction ID:</td>
                                    <td class="fw-bold">{{ $transaction->transaction_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Type:</td>
                                    <td>
                                        @if($transaction->type === 'purchase')
                                            <span class="badge bg-success">Purchase</span>
                                        @else
                                            <span class="badge bg-info">Top-up</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Amount:</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
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
                                </tr>
                                <tr>
                                    <td class="text-muted">Created At:</td>
                                    <td>{{ $transaction->created_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                @if($transaction->processed_at)
                                <tr>
                                    <td class="text-muted">Processed At:</td>
                                    <td>{{ $transaction->processed_at->format('d M Y H:i:s') }}</td>
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
                                    <h4 class="text-white mb-0">{{ substr($transaction->user->name, 0, 1) }}</h4>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $transaction->user->name }}</h5>
                                    <p class="text-muted mb-1">{{ $transaction->user->email }}</p>
                                    <small class="text-muted">Current Balance: <span class="fw-bold text-success">Rp {{ number_format($transaction->user->balance, 0, ',', '.') }}</span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($transaction->type === 'purchase' && $transaction->game)
                <div class="mb-4">
                    <h6>Game Information</h6>
                    <div class="d-flex align-items-center">
                        @if($transaction->game->cover_image)
                            <img src="{{ $transaction->game->cover_image_url }}" alt="{{ $transaction->game->name }}" 
                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-gamepad text-white fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $transaction->game->name }}</h5>
                            <p class="text-muted mb-1">{{ $transaction->game->category->name }}</p>
                            <p class="fw-bold text-success mb-0">Rp {{ number_format($transaction->game->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($transaction->details)
                <div class="mb-4">
                    <h6>Transaction Details</h6>
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0">{{ json_encode($transaction->details, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif

                @if($transaction->notes)
                <div class="mb-4">
                    <h6>Notes</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $transaction->notes }}
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Back to Transactions
                    </a>
                    @if($transaction->user)
                        <a href="{{ route('users.show', $transaction->user->id) }}" class="btn btn-primary btn-modern">
                            <i class="fas fa-user me-2"></i>View User
                        </a>
                    @endif
                    @if($transaction->game)
                        <a href="{{ route('games.show', $transaction->game->id) }}" class="btn btn-success btn-modern">
                            <i class="fas fa-gamepad me-2"></i>View Game
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection