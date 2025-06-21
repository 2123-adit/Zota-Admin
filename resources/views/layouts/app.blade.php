<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZOTA Game Store Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #16213e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
            position: relative;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 80, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 80, 120, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 12px;
            margin: 3px 0;
            padding: 12px 16px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(120, 80, 255, 0.3) 0%, rgba(255, 80, 120, 0.2) 100%);
            color: white;
            transform: translateX(8px);
            border: 1px solid rgba(120, 80, 255, 0.5);
            box-shadow: 0 5px 15px rgba(120, 80, 255, 0.2);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            position: relative;
        }

        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(45deg, transparent 24%, rgba(255, 255, 255, 0.02) 25%, rgba(255, 255, 255, 0.02) 26%, transparent 27%);
            background-size: 30px 30px;
            animation: gridMove 20s linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 30px); }
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .card {
            border: none;
            border-radius: 20px;
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .navbar {
            background: rgba(10, 10, 15, 0.95) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            border-bottom: 2px solid rgba(120, 80, 255, 0.3);
        }

        .navbar h5 {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: #fff !important;
            text-shadow: 0 0 10px rgba(120, 80, 255, 0.5);
        }

        .admin-info {
            background: linear-gradient(135deg, rgba(120, 80, 255, 0.2) 0%, rgba(255, 80, 120, 0.1) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .admin-info .admin-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #7850ff, #ff5078);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 0 15px rgba(120, 80, 255, 0.3);
        }

        .sidebar-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.5rem;
            color: #fff;
            text-shadow: 0 0 15px rgba(120, 80, 255, 0.8);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .logout-btn {
            background: linear-gradient(135deg, rgba(255, 80, 120, 0.2) 0%, rgba(255, 50, 100, 0.1) 100%);
            border: 1px solid rgba(255, 80, 120, 0.3);
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, rgba(255, 80, 120, 0.4) 0%, rgba(255, 50, 100, 0.2) 100%);
            border-color: rgba(255, 80, 120, 0.5);
            color: white !important;
            transform: translateX(8px);
        }

        .live-clock {
            font-family: 'Orbitron', monospace;
            color: #50ff78;
            font-weight: 600;
            font-size: 1rem;
            text-shadow: 0 0 5px rgba(80, 255, 120, 0.3);
            display: flex;
            align-items: center;
        }

        .dropdown-toggle {
            background: linear-gradient(135deg, rgba(120, 80, 255, 0.2) 0%, rgba(255, 80, 120, 0.1) 100%);
            border: 1px solid rgba(120, 80, 255, 0.3);
            color: #fff !important;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
        }

        .dropdown-toggle:hover {
            background: linear-gradient(135deg, rgba(120, 80, 255, 0.3) 0%, rgba(255, 80, 120, 0.2) 100%);
            border-color: rgba(120, 80, 255, 0.5);
        }

        .dropdown-menu {
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(120, 80, 255, 0.2);
            color: #fff;
        }

        .alert {
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(80, 255, 120, 0.1);
            border-color: rgba(80, 255, 120, 0.3);
            color: #50ff78;
        }

        .alert-danger {
            background: rgba(255, 80, 120, 0.1);
            border-color: rgba(255, 80, 120, 0.3);
            color: #ff5078;
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
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="sidebar-title">
                        <i class="fas fa-gamepad me-2"></i>ZOTA ADMIN
                    </div>

                    <!-- Admin Info -->
                    @auth('admin')
                    <div class="admin-info text-white">
                        <div class="d-flex align-items-center">
                            <div class="admin-avatar me-3">
                                {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold" style="font-family: 'Rajdhani', sans-serif; font-size: 1.1rem;">
                                    {{ Auth::guard('admin')->user()->name }}
                                </div>
                                <small class="opacity-75" style="font-family: 'Rajdhani', sans-serif;">
                                    @if(Auth::guard('admin')->user()->role === 'super_admin')
                                        <i class="fas fa-crown me-1" style="color: #ffc850;"></i>Super Admin
                                    @else
                                        <i class="fas fa-user-tie me-1" style="color: #5078ff;"></i>Admin
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endauth
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>Command Center
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i>Players
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('games.*') ? 'active' : '' }}" href="{{ route('games.index') }}">
                                <i class="fas fa-gamepad"></i>Game Library
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('topups.*') ? 'active' : '' }}" href="{{ route('topups.index') }}">
                                <i class="fas fa-credit-card"></i>Wallet Top-ups
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                                <i class="fas fa-exchange-alt"></i>Transactions
                            </a>
                        </li>
                        
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                        
                        <li class="nav-item">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="nav-link logout-btn text-start w-100 border-0 bg-transparent" 
                                        onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <div class="container-fluid">
                        <h5 class="mb-0">@yield('page-title', 'Command Center')</h5>
                        <div class="d-flex align-items-center">
                            <div class="live-clock me-3" id="liveClock">
                                <i class="fas fa-clock me-1"></i>Loading...
                            </div>
                            @auth('admin')
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user me-1"></i>{{ Auth::guard('admin')->user()->name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <span class="dropdown-item-text">
                                                <small class="text-muted">
                                                    @if(Auth::guard('admin')->user()->role === 'super_admin')
                                                        <i class="fas fa-crown me-1" style="color: #ffc850;"></i>Super Admin
                                                    @else
                                                        <i class="fas fa-user-tie me-1" style="color: #5078ff;"></i>Admin
                                                    @endif
                                                </small>
                                            </span>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to logout?')">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        </div>
                    </div>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            
            // Format untuk Jakarta timezone
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
            
            const formatter = new Intl.DateTimeFormat('en-US', options);
            const parts = formatter.formatToParts(now);
            
            const day = parts.find(part => part.type === 'day').value;
            const month = parts.find(part => part.type === 'month').value;
            const year = parts.find(part => part.type === 'year').value;
            const hour = parts.find(part => part.type === 'hour').value;
            const minute = parts.find(part => part.type === 'minute').value;
            const second = parts.find(part => part.type === 'second').value;
            
            const timeString = `${day} ${month} ${year}, ${hour}:${minute}:${second}`;
            
            const clockElement = document.getElementById('liveClock');
            if (clockElement) {
                clockElement.innerHTML = `<i class="fas fa-clock me-1"></i>${timeString}`;
            }
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth sidebar navigation
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(8px) scale(1.02)';
                });
                
                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(0) scale(1)';
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>