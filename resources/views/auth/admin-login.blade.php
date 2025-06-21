<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOTA Admin Portal - Game Store Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 80, 255, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 80, 120, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(80, 255, 120, 0.2) 0%, transparent 50%),
                linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
            animation: backgroundShift 20s ease-in-out infinite;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(45deg, transparent 24%, rgba(255, 255, 255, 0.05) 25%, rgba(255, 255, 255, 0.05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, 0.05) 75%, rgba(255, 255, 255, 0.05) 76%, transparent 77%);
            background-size: 50px 50px;
            animation: gridMove 30s linear infinite;
        }

        @keyframes backgroundShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        .particle:nth-child(1) { top: 10%; left: 20%; width: 4px; height: 4px; animation-delay: 0s; }
        .particle:nth-child(2) { top: 70%; left: 80%; width: 6px; height: 6px; animation-delay: 5s; }
        .particle:nth-child(3) { top: 40%; left: 10%; width: 3px; height: 3px; animation-delay: 10s; }
        .particle:nth-child(4) { top: 30%; left: 90%; width: 5px; height: 5px; animation-delay: 7s; }
        .particle:nth-child(5) { top: 80%; left: 30%; width: 4px; height: 4px; animation-delay: 12s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10%, 90% { opacity: 1; }
            50% { transform: translateY(-100px) rotate(180deg); }
        }

        /* Main Container */
        .login-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(15, 15, 25, 0.95);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            box-shadow: 
                0 25px 45px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                0 0 50px rgba(120, 80, 255, 0.2);
            max-width: 450px;
            width: 100%;
            overflow: hidden;
            position: relative;
            animation: cardGlow 3s ease-in-out infinite alternate;
        }

        @keyframes cardGlow {
            0% { box-shadow: 0 25px 45px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 0 50px rgba(120, 80, 255, 0.2); }
            100% { box-shadow: 0 25px 45px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 0 80px rgba(120, 80, 255, 0.4); }
        }

        .login-header {
            background: linear-gradient(135deg, rgba(120, 80, 255, 0.2) 0%, rgba(255, 80, 120, 0.2) 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.05) 50%, transparent 60%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .login-header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 20px rgba(120, 80, 255, 0.8);
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .game-icons {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }

        .game-icons i {
            margin: 0 10px;
            animation: iconFloat 4s ease-in-out infinite;
        }

        .game-icons i:nth-child(2) { animation-delay: 1s; }
        .game-icons i:nth-child(3) { animation-delay: 2s; }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-body {
            padding: 2.5rem;
            background: rgba(10, 10, 15, 0.5);
        }

        .form-label {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-right: none;
            border-radius: 15px 0 0 15px;
            color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            border-radius: 0 15px 15px 0;
            color: #fff;
            padding: 0.9rem 1.2rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #7850ff;
            box-shadow: 0 0 0 0.2rem rgba(120, 80, 255, 0.3), 0 0 20px rgba(120, 80, 255, 0.2);
            color: #fff;
        }

        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #7850ff;
            background: rgba(120, 80, 255, 0.2);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }

        .form-check-input:checked {
            background: linear-gradient(135deg, #7850ff, #ff5078);
            border-color: #7850ff;
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #7850ff 0%, #ff5078 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Rajdhani', sans-serif;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(120, 80, 255, 0.4);
            background: linear-gradient(135deg, #8a5fff 0%, #ff6088 100%);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: #fff;
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
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

        .text-danger {
            color: #ff5078 !important;
        }

        /* Gaming Elements */
        .power-button {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .status-indicator {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #50ff78;
            margin-right: 8px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                margin: 10px;
                border-radius: 20px;
            }
            
            .login-header h2 {
                font-size: 1.8rem;
            }
            
            .login-body {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="status-indicator">
                <div class="status-dot"></div>
                SYSTEM ONLINE
            </div>
            <div class="power-button">
                <i class="fas fa-power-off"></i>
            </div>
            
            <div class="login-header">
                <div class="game-icons">
                    <i class="fas fa-gamepad"></i>
                    <i class="fas fa-trophy"></i>
                    <i class="fas fa-rocket"></i>
                </div>
                <h2>ZOTA ADMIN</h2>
                <p>Game Store Control Center</p>
            </div>
            
            <div class="login-body">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user-shield me-2"></i>Administrator Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required placeholder="admin@zota.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-key me-2"></i>Access Code
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   required placeholder="Enter your access code">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            <i class="fas fa-memory me-1"></i>Remember Session
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-rocket me-2"></i>Launch Control Panel
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small style="color: rgba(255,255,255,0.5);">
                        <i class="fas fa-shield-alt me-1"></i>
                        Secure Gaming Administration Portal v2.0
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate form elements on focus
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Power button click effect
            const powerBtn = document.querySelector('.power-button');
            powerBtn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });

            // Add typing effect to placeholder (optional)
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput && !emailInput.value) {
                let placeholder = 'admin@zota.com';
                let index = 0;
                emailInput.placeholder = '';
                
                function typeEffect() {
                    if (index < placeholder.length) {
                        emailInput.placeholder += placeholder.charAt(index);
                        index++;
                        setTimeout(typeEffect, 100);
                    }
                }
                
                setTimeout(typeEffect, 1000);
            }
        });
    </script>
</body>
</html>