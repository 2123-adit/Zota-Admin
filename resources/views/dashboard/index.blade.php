@extends('layouts.app')

@section('title', 'Dashboard - ZOTA Admin')
@section('page-title', 'Pusat Kendali')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap');
    
    .main-content {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #16213e 100%);
        min-height: 100vh;
        font-family: 'Rajdhani', sans-serif;
    }

    .navbar {
        background: rgba(10, 10, 15, 0.95) !important;
        backdrop-filter: blur(20px);
        border-bottom: 2px solid rgba(120, 80, 255, 0.3);
    }

    .navbar h5 {
        font-family: 'Orbitron', monospace;
        font-weight: 700;
        color: #fff !important;
        text-shadow: 0 0 10px rgba(120, 80, 255, 0.5);
    }

    .live-clock {
        font-family: 'Orbitron', monospace;
        color: #50ff78;
        font-weight: 600;
        text-shadow: 0 0 5px rgba(80, 255, 120, 0.3);
    }

    /* Kartu Statistik Gaming */
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 80, 120, 0.1) 0%, rgba(120, 80, 255, 0.1) 100%);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(120, 80, 255, 0.5);
        box-shadow: 0 15px 35px rgba(120, 80, 255, 0.2);
    }

    .stat-card.users {
        background: linear-gradient(135deg, rgba(255, 80, 120, 0.15) 0%, rgba(255, 50, 100, 0.1) 100%);
        border-color: rgba(255, 80, 120, 0.3);
    }

    .stat-card.games {
        background: linear-gradient(135deg, rgba(80, 255, 120, 0.15) 0%, rgba(50, 255, 100, 0.1) 100%);
        border-color: rgba(80, 255, 120, 0.3);
    }

    .stat-card.topups {
        background: linear-gradient(135deg, rgba(255, 200, 80, 0.15) 0%, rgba(255, 180, 50, 0.1) 100%);
        border-color: rgba(255, 200, 80, 0.3);
    }

    .stat-card.sales {
        background: linear-gradient(135deg, rgba(120, 80, 255, 0.15) 0%, rgba(100, 50, 255, 0.1) 100%);
        border-color: rgba(120, 80, 255, 0.3);
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.7;
        background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.7) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .stat-number {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 900;
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        margin: 0;
    }

    .stat-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .stat-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Tabel Gaming */
    .gaming-table {
        background: rgba(10, 10, 15, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        overflow: hidden;
    }

    .gaming-table .card-header {
        background: linear-gradient(135deg, rgba(120, 80, 255, 0.2) 0%, rgba(255, 80, 120, 0.2) 100%);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
    }

    .gaming-table .card-title {
        font-family: 'Orbitron', monospace;
        font-weight: 700;
        color: #fff;
        margin: 0;
        text-shadow: 0 0 10px rgba(120, 80, 255, 0.5);
    }

    .gaming-table .table {
        color: #fff;
        margin: 0;
    }

    .gaming-table .table th {
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem;
        font-size: 0.9rem;
    }

    .gaming-table .table td {
        border-color: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        vertical-align: middle;
    }

    .gaming-table .table tbody tr {
        transition: all 0.3s ease;
    }

    .gaming-table .table tbody tr:hover {
        background: rgba(120, 80, 255, 0.1);
        transform: scale(1.01);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #7850ff, #ff5078);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 0.75rem;
        box-shadow: 0 0 15px rgba(120, 80, 255, 0.3);
    }

    .badge {
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #50ff78, #00ff88) !important;
        color: #000;
        box-shadow: 0 0 10px rgba(80, 255, 120, 0.3);
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #5078ff, #0088ff) !important;
        box-shadow: 0 0 10px rgba(80, 120, 255, 0.3);
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #ffc850, #ff8800) !important;
        color: #000;
        box-shadow: 0 0 10px rgba(255, 200, 80, 0.3);
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #7850ff, #ff5078) !important;
        box-shadow: 0 0 10px rgba(120, 80, 255, 0.3);
    }

    /* Kartu Game Terpopuler */
    .top-games-card {
        background: rgba(10, 10, 15, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(20px);
    }

    .top-games-card .card-header {
        background: linear-gradient(135deg, rgba(255, 200, 80, 0.2) 0%, rgba(255, 150, 50, 0.2) 100%);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
    }

    .game-item {
        padding: 1rem;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.05);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .game-item:hover {
        background: rgba(120, 80, 255, 0.1);
        transform: translateX(5px);
        border-color: rgba(120, 80, 255, 0.3);
    }

    .game-item h6 {
        color: #fff;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .game-item small {
        color: rgba(255, 255, 255, 0.6);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #7850ff, #ff5078);
        border-radius: 4px;
    }

    /* Animasi Loading */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 2rem;
        }
        
        .stat-icon {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Kartu Statistik -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card users">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Pengguna</div>
                        <h2 class="stat-number">{{ number_format($stats['total_users']) }}</h2>
                        <div class="stat-subtitle">{{ $stats['active_users'] }} pemain aktif</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card games">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Game Aktif</div>
                        <h2 class="stat-number">{{ number_format($stats['total_games']) }}</h2>
                        <div class="stat-subtitle">Siap dimainkan</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card topups">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Top-up Tertunda</div>
                        <h2 class="stat-number">{{ number_format($stats['pending_topups']) }}</h2>
                        <div class="stat-subtitle">Perlu persetujuan</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card sales">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Pendapatan Hari Ini</div>
                        <h2 class="stat-number">{{ number_format($stats['today_sales']/1000, 0) }}K</h2>
                        <div class="stat-subtitle">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }} total</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <!-- Transaksi Terbaru -->
    <div class="col-lg-8">
        <div class="card gaming-table">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-exchange-alt me-2"></i>Transaksi Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Pemain</th>
                                <th>Aksi</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar">
                                            {{ substr($transaction->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $transaction->user->name }}</div>
                                            <small class="text-muted">ID: {{ $transaction->user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($transaction->type === 'purchase')
                                        <span class="badge bg-success">
                                            <i class="fas fa-shopping-cart me-1"></i>Pembelian
                                        </span>
                                        <br><small class="text-muted">{{ $transaction->game->name ?? 'N/A' }}</small>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="fas fa-wallet me-1"></i>Top-up
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold" style="color: #50ff78;">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Selesai
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Tertunda
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>{{ ucfirst($transaction->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                    <small>{{ $transaction->created_at->format('H:i') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Terpopuler -->
    <div class="col-lg-4">
        <div class="card top-games-card">
            <div class="card-header">
                <h5 class="card-title" style="color: #fff; font-family: 'Orbitron', monospace;">
                    <i class="fas fa-trophy me-2" style="color: #ffc850;"></i>Game Terpopuler
                </h5>
            </div>
            <div class="card-body">
                @foreach($topGames as $index => $game)
                <div class="game-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="badge" style="background: linear-gradient(135deg, #ffc850, #ff8800); color: #000; font-size: 0.8rem;">
                                    #{{ $index + 1 }}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $game->name }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-fire me-1" style="color: #ff5078;"></i>
                                    {{ $game->transactions_count }} penjualan
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-primary">
                                Rp {{ number_format($game->price/1000, 0) }}K
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pembaruan Jam Langsung
    function updateClock() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Jakarta',
            year: 'numeric',
            month: 'short', 
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        
        const timeString = now.toLocaleDateString('id-ID', options);
        const clockElement = document.querySelector('.live-clock');
        if (clockElement) {
            clockElement.innerHTML = `<i class="fas fa-clock me-1"></i>${timeString}`;
        }
    }

    // Perbarui jam segera dan kemudian setiap detik
    updateClock();
    setInterval(updateClock, 1000);

    // Tambahkan animasi pulse pada item tertunda
    const pendingBadges = document.querySelectorAll('.badge.bg-warning');
    pendingBadges.forEach(badge => {
        badge.classList.add('pulse');
    });

    // Tambahkan efek hover pada kartu statistik
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Animasi penghitung angka yang halus (opsional)
    const numbers = document.querySelectorAll('.stat-number');
    numbers.forEach(number => {
        const target = parseInt(number.textContent.replace(/,/g, ''));
        let current = 0;
        const increment = target / 50;
        
        const counter = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(counter);
            }
            number.textContent = Math.floor(current).toLocaleString();
        }, 30);
    });
});
</script>
@endpush