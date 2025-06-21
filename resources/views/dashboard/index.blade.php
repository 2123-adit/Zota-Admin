@extends('layouts.app')

@section('title', 'Dashboard - ZOTA Admin')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row g-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-1">Total Users</h6>
                        <h2 class="mb-0 text-white">{{ number_format($stats['total_users']) }}</h2>
                        <small class="text-white-50">{{ $stats['active_users'] }} active</small>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-1">Total Games</h6>
                        <h2 class="mb-0 text-white">{{ number_format($stats['total_games']) }}</h2>
                        <small class="text-white-50">Active games</small>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-gamepad fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-1">Pending Top-ups</h6>
                        <h2 class="mb-0 text-white">{{ number_format($stats['pending_topups']) }}</h2>
                        <small class="text-white-50">Need approval</small>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-credit-card fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-1">Today's Sales</h6>
                        <h2 class="mb-0 text-white">Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</h2>
                        <small class="text-white-50">Total: Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</small>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <!-- Recent Transactions -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exchange-alt me-2 text-primary"></i>Recent Transactions
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <small class="text-white fw-bold">{{ substr($transaction->user->name, 0, 1) }}</small>
                                        </div>
                                        {{ $transaction->user->name }}
                                    </div>
                                </td>
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

    <!-- Top Games -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2 text-warning"></i>Top Games
                </h5>
            </div>
            <div class="card-body">
                @foreach($topGames as $game)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">{{ $game->name }}</h6>
                        <small class="text-muted">{{ $game->transactions_count }} sales</small>
                    </div>
                    <span class="badge bg-primary">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                </div>
                @if(!$loop->last)
                <hr class="my-2">
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection