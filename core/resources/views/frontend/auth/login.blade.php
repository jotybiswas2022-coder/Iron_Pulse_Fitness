<div class="login-container">

    <!-- Background -->
    <div class="grid-bg"></div>
    <div class="glow-orb"></div>
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 d-flex justify-content-center">

                <div class="card login-card">

                    <!-- HEADER -->
                    <div class="card-header login-header text-center">
                        <span class="icon-lock">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <div>Iron Pulse Gym</div>
                        <small style="font-size: 14px; letter-spacing: 1px; opacity: 0.9;">
                            Member Login
                        </small>
                    </div>

                    <div class="card-body login-body">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- EMAIL -->
                            <div class="form-group-custom">
                                <label for="email" class="login-label pb-2">
                                    <i class="bi bi-envelope-fill me-2"></i>Email Address
                                </label>

                                <input id="email" type="email"
                                       class="form-control login-input @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@example.com"
                                       required autofocus>

                                @error('email')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group-custom">
                                <label for="password" class="login-label">
                                    <i class="bi bi-key-fill me-2"></i>Password
                                </label>

                                <input id="password" type="password"
                                       class="form-control login-input @error('password') is-invalid @enderror"
                                       name="password"
                                       placeholder="••••••••"
                                       required>

                                @error('password')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- REMEMBER -->
                            <div class="form-check custom-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label login-remember" for="remember">
                                    <i class="bi bi-check-circle me-1"></i>Remember Me
                                </label>
                            </div>

                            <!-- BUTTON -->
                            <button type="submit" class="login-btn mt-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>

                            @if (Route::has('password.request'))
                            <div class="text-end">
                                <a class="login-link" href="{{ route('password.request') }}">
                                    <i class="bi bi-question-circle me-1"></i>Forgot Password?
                                </a>
                            </div>
                            @endif

                        </form>

                        <div class="divider">
                            <span>OR</span>
                        </div>

                        <div class="text-center">
                            <span class="signup-text me-2">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="signup-btn">
                                <i class="bi bi-person-plus me-1"></i>Sign Up
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Create floating particles
    const particlesContainer = document.getElementById('particles');
    const particleCount = 30;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random starting position
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
        
        particlesContainer.appendChild(particle);
    }

    // Form validation effect
    const form = document.querySelector('form');
    const inputs = document.querySelectorAll('.login-input');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
                this.nextElementSibling.classList.remove('d-none');
            } else {
                this.classList.remove('is-invalid');
                this.nextElementSibling.classList.add('d-none');
            }
        });

        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.nextElementSibling.classList.add('d-none');
            }
        });
    });

    // Button ripple effect on click
    const loginBtn = document.querySelector('.login-btn');
    loginBtn.addEventListener('click', function(e) {
        
        // Validate before submitting
        let isValid = true;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                input.nextElementSibling.classList.remove('d-none');
                isValid = false;
            }
        });

        if (isValid) {
            // Add success animation
            this.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Success!';
            this.style.background = 'linear-gradient(135deg, #00C853 0%, #00E676 100%)';
            
            setTimeout(() => {
                alert('Login successful! (This is a demo)');
                location.reload();
            }, 1000);
        }
    });

    // Add gym-themed cursor effect
    document.addEventListener('mousemove', function(e) {
        const orb = document.querySelector('.glow-orb');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        orb.style.left = (45 + x * 10) + '%';
        orb.style.top = (45 + y * 10) + '%';
    });
</script>

<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --deep-black: #0F0F0F;
            --power-red: #E50914;
            --accent-red: #FF3B3B;
            --background: #121212;
            --text-white: #FFFFFF;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: var(--background);
            color: var(--text-white);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--deep-black) 0%, var(--background) 50%, #1a0000 100%);
        }

        /* Animated Grid Background */
        .grid-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(229, 9, 20, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(229, 9, 20, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            z-index: 1;
        }

        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Glow Orb - Pulsing Energy */
        .glow-orb {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--power-red) 0%, transparent 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            animation: pulse 4s ease-in-out infinite;
            z-index: 1;
            filter: blur(80px);
        }

        @keyframes pulse {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.5;
            }
        }

        /* Floating Particles - Gym Energy */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-red);
            border-radius: 50%;
            animation: float 15s infinite;
            opacity: 0.6;
            box-shadow: 0 0 10px var(--accent-red);
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        .container {
            position: relative;
            z-index: 2;
        }

        /* Login Card */
        .login-card {
            background: rgba(15, 15, 15, 0.9);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 20px;
            box-shadow: 
                0 10px 40px rgba(229, 9, 20, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
            animation: cardEntry 0.8s ease-out;
        }

        @keyframes cardEntry {
            0% {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Gym-themed border animation */
        .login-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--power-red), var(--accent-red), var(--power-red));
            border-radius: 20px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s;
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        .login-card:hover::before {
            opacity: 0.5;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Header */
        .login-header {
            background: linear-gradient(135deg, var(--power-red) 0%, var(--accent-red) 100%);
            color: var(--text-white);
            padding: 25px;
            font-size: 28px;
            font-weight: bold;
            border: none;
            border-radius: 18px 18px 0 0;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Gym logo icon with dumbbell animation */
        .icon-lock {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 10px;
            margin-bottom: 10px;
            animation: dumbbellLift 2s ease-in-out infinite;
        }

        .icon-lock i {
            font-size: 30px;
        }

        @keyframes dumbbellLift {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-10px) rotate(-5deg);
            }
            75% {
                transform: translateY(-10px) rotate(5deg);
            }
        }

        /* Body */
        .login-body {
            padding: 40px 35px;
            background: rgba(18, 18, 18, 0.6);
        }

        /* Form Groups */
        .form-group-custom {
            margin-bottom: 25px;
            position: relative;
        }

        .login-label {
            color: var(--text-white);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-input {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 10px;
            color: var(--text-white);
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--power-red);
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.4);
            color: var(--text-white);
            outline: none;
        }

        .login-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Checkbox */
        .custom-check {
            margin: 20px 0;
        }

        .custom-check .form-check-input {
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(229, 9, 20, 0.5);
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .custom-check .form-check-input:checked {
            background-color: var(--power-red);
            border-color: var(--power-red);
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.6);
        }

        .custom-check .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(229, 9, 20, 0.25);
        }

        .login-remember {
            color: var(--text-white);
            margin-left: 8px;
            cursor: pointer;
        }

        /* Login Button - Gym Power Style */
        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--power-red) 0%, var(--accent-red) 100%);
            border: none;
            border-radius: 10px;
            color: var(--text-white);
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(229, 9, 20, 0.4);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .login-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.6);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        /* Links */
        .login-link {
            color: var(--accent-red);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 15px;
        }

        .login-link:hover {
            color: var(--text-white);
            text-shadow: 0 0 10px var(--power-red);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: rgba(255, 255, 255, 0.5);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(229, 9, 20, 0.3);
        }

        .divider span {
            padding: 0 15px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Sign Up Section */
        .signup-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .signup-btn {
            color: var(--power-red);
            text-decoration: none;
            font-weight: bold;
            padding: 8px 20px;
            border: 2px solid var(--power-red);
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signup-btn:hover {
            background: var(--power-red);
            color: var(--text-white);
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
            transform: scale(1.05);
        }

        /* Invalid Feedback */
        .invalid-feedback {
            color: var(--accent-red);
            font-size: 13px;
            margin-top: 5px;
        }

        .is-invalid {
            border-color: var(--accent-red) !important;
            box-shadow: 0 0 10px rgba(255, 59, 59, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-header {
                font-size: 24px;
                padding: 20px;
            }

            .login-body {
                padding: 30px 25px;
            }

            .login-btn {
                font-size: 16px;
                padding: 12px;
            }

            .glow-orb {
                width: 300px;
                height: 300px;
            }
        }

        /* Additional Gym-themed animations */
        @keyframes strengthPulse {
            0%, 100% {
                box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(229, 9, 20, 0.6);
            }
        }

        .login-card {
            animation: cardEntry 0.8s ease-out, strengthPulse 3s ease-in-out infinite 1s;
        }
    </style>