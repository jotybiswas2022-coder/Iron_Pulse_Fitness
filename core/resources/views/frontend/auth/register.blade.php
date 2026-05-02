<div class="login-container">
    <div class="particles" id="particles"></div>
    
    <!-- Floating Gym Icons -->
    <div class="gym-icons">
        <i class="bi bi-heart-pulse gym-icon"></i>
        <i class="bi bi-trophy gym-icon"></i>
        <i class="bi bi-lightning-charge gym-icon"></i>
        <i class="bi bi-fire gym-icon"></i>
    </div>

    <!-- Power Lines -->
    <div class="power-lines">
        <div class="power-line"></div>
        <div class="power-line"></div>
        <div class="power-line"></div>
    </div>

    <!-- Motivational Text -->
    <div class="motivation-text">No Pain, No Gain</div>

    <div class="login-wrapper">
        <div class="card login-card">
            <div class="card-header login-header">
                <div class="header-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                Iron Pulse Gym
            </div>

            <div class="card-body login-body">

                <!-- ✅ FORM FIX -->
                <form method="POST" action="{{ route('register') }}" autocomplete="off">
                    @csrf

                    <!-- NAME -->
                    <div class="input-group-animated">
                        <label for="name" class="login-label">
                            <i class="bi bi-person-fill"></i> Name
                        </label>

                        <input id="name" type="text"
                               class="form-control login-input @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Enter your name"
                               required autofocus autocomplete="name">

                        <!-- ✅ ERROR -->
                        @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="input-group-animated">
                        <label for="email" class="login-label">
                            <i class="bi bi-envelope-fill"></i> Email Address
                        </label>

                        <input id="email" type="email"
                               class="form-control login-input @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autocomplete="email">

                        <!-- ✅ ERROR -->
                        @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="input-group-animated">
                        <label for="password" class="login-label">
                            <i class="bi bi-lock-fill"></i> Password
                        </label>

                        <input id="password" type="password"
                               class="form-control login-input @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="••••••••"
                               required autocomplete="new-password">

                        <!-- ✅ ERROR -->
                        @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="input-group-animated">
                        <label for="password-confirm" class="login-label">
                            <i class="bi bi-shield-lock-fill"></i> Confirm Password
                        </label>

                        <input id="password-confirm" type="password"
                               class="form-control login-input"
                               name="password_confirmation"
                               placeholder="••••••••"
                               required autocomplete="new-password">
                    </div>

                    <!-- BUTTON + LOGIN LINK -->
                    <div class="input-group-animated mt-3 d-flex flex-column gap-3">
                        <button type="submit" class="btn login-btn">
                            <i class="bi bi-lightning-charge-fill"></i> Register Now
                        </button>

                        <!-- ✅ LOGIN ROUTE -->
                        <a href="{{ route('login') }}" class="login-link">
                            <i class="bi bi-box-arrow-in-right"></i> Already have an account? Login
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation animation
        document.querySelector('form').addEventListener('submit', function(e) {
            
            const inputs = this.querySelectorAll('.login-input');
            let isValid = true;
            
            inputs.forEach(input => {
                const feedback = input.nextElementSibling;
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'flex';
                    }
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'none';
                    }
                }
            });

            // Password match validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password-confirm');
            
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            }

            if (isValid) {
                // Success animation
                const btn = this.querySelector('.login-btn');
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Registration Successful!';
                btn.style.background = 'linear-gradient(135deg, #00C851 0%, #007E33 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-lightning-charge-fill"></i> Register Now';
                    btn.style.background = 'linear-gradient(135deg, var(--power-red) 0%, var(--accent-red) 100%)';
                }, 2000);
            }
        });

        // Remove validation on input
        document.querySelectorAll('.login-input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        });

        // Create dynamic particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: absolute;
                width: ${Math.random() * 4 + 1}px;
                height: ${Math.random() * 4 + 1}px;
                background: var(--power-red);
                border-radius: 50%;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                opacity: ${Math.random() * 0.5};
                animation: particleFloat ${Math.random() * 10 + 5}s infinite ease-in-out;
                animation-delay: ${Math.random() * 5}s;
            `;
            particlesContainer.appendChild(particle);
        }

        // Add particle animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes particleFloat {
                0%, 100% {
                    transform: translate(0, 0);
                }
                25% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px);
                }
                50% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px);
                }
                75% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px);
                }
            }
        `;
        document.head.appendChild(style);

        // Motivational quotes rotation
        const motivationTexts = [
            "No Pain, No Gain",
            "Train Insane",
            "Push Your Limits",
            "Beast Mode On",
            "Iron Pulse Power"
        ];
        
        let currentIndex = 0;
        const motivationElement = document.querySelector('.motivation-text');
        
        setInterval(() => {
            currentIndex = (currentIndex + 1) % motivationTexts.length;
            motivationElement.textContent = motivationTexts[currentIndex];
        }, 8000);
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --deep-black: #0F0F0F;
            --power-red: #E50914;
            --accent-red: #FF3B3B;
            --background: #121212;
            --text-white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background);
            color: var(--text-white);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--deep-black) 0%, var(--background) 50%, #1a0000 100%);
        }

        /* Animated Background Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particles::before,
        .particles::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 8s ease-in-out infinite;
        }

        .particles::before {
            background: var(--power-red);
            opacity: 0.1;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .particles::after {
            background: var(--accent-red);
            opacity: 0.08;
            bottom: -150px;
            right: -150px;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(100px, -100px) scale(1.1);
            }
            66% {
                transform: translate(-100px, 100px) scale(0.9);
            }
        }

        /* Gym Equipment Floating Animation */
        .gym-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .gym-icon {
            position: absolute;
            font-size: 40px;
            color: var(--power-red);
            opacity: 0.1;
            animation: gymFloat 15s infinite ease-in-out;
        }

        .gym-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .gym-icon:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 3s;
        }

        .gym-icon:nth-child(3) {
            bottom: 15%;
            left: 15%;
            animation-delay: 6s;
        }

        .gym-icon:nth-child(4) {
            bottom: 25%;
            right: 10%;
            animation-delay: 9s;
        }

        @keyframes gymFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                transform: translateY(-50px) rotate(180deg);
                opacity: 0.2;
            }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
        }

        .login-card {
            background: rgba(15, 15, 15, 0.95);
            border: 2px solid var(--power-red);
            border-radius: 20px;
            box-shadow: 
                0 0 40px rgba(229, 9, 20, 0.3),
                0 0 80px rgba(229, 9, 20, 0.1),
                inset 0 0 60px rgba(229, 9, 20, 0.05);
            overflow: hidden;
            animation: cardPulse 3s ease-in-out infinite;
            backdrop-filter: blur(10px);
        }

        @keyframes cardPulse {
            0%, 100% {
                box-shadow: 
                    0 0 40px rgba(229, 9, 20, 0.3),
                    0 0 80px rgba(229, 9, 20, 0.1),
                    inset 0 0 60px rgba(229, 9, 20, 0.05);
            }
            50% {
                box-shadow: 
                    0 0 50px rgba(229, 9, 20, 0.4),
                    0 0 100px rgba(229, 9, 20, 0.2),
                    inset 0 0 80px rgba(229, 9, 20, 0.08);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--power-red) 0%, var(--accent-red) 100%);
            padding: 30px;
            text-align: center;
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--text-white);
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: headerShine 3s infinite;
        }

        @keyframes headerShine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .header-icon i {
            font-size: 60px;
            animation: iconBounce 2s ease-in-out infinite;
        }

        @keyframes iconBounce {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-10px) scale(1.1);
            }
        }

        .login-body {
            padding: 40px;
            background: var(--deep-black);
        }

        .input-group-animated {
            position: relative;
            margin-bottom: 25px;
        }

        .login-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text-white);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-label::before {
            content: '';
            width: 4px;
            height: 18px;
            background: var(--power-red);
            border-radius: 2px;
            animation: labelPulse 2s ease-in-out infinite;
        }

        @keyframes labelPulse {
            0%, 100% {
                opacity: 1;
                transform: scaleY(1);
            }
            50% {
                opacity: 0.6;
                transform: scaleY(0.8);
            }
        }

        .login-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(18, 18, 18, 0.8);
            border: 2px solid rgba(229, 9, 20, 0.3);
            border-radius: 10px;
            color: var(--text-white);
            font-size: 16px;
            transition: all 0.4s ease;
            outline: none;
        }

        .login-input:focus {
            border-color: var(--power-red);
            background: rgba(18, 18, 18, 1);
            box-shadow: 
                0 0 20px rgba(229, 9, 20, 0.3),
                inset 0 0 20px rgba(229, 9, 20, 0.1);
            transform: translateY(-2px);
        }

        .login-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .login-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--power-red) 0%, var(--accent-red) 100%);
            border: none;
            border-radius: 10px;
            color: var(--text-white);
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.4);
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
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.6);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-link {
            display: block;
            text-align: center;
            color: var(--power-red);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            position: relative;
        }

        .login-link::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--power-red);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .login-link:hover {
            color: var(--accent-red);
        }

        .login-link:hover::after {
            width: 100%;
        }

        .invalid-feedback {
            color: var(--accent-red);
            font-size: 13px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .invalid-feedback::before {
            content: '⚠';
            font-size: 16px;
        }

        .is-invalid {
            border-color: var(--accent-red) !important;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Gym Motivational Text Animation */
        .motivation-text {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 18px;
            font-weight: 700;
            color: var(--power-red);
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0;
            animation: motivationFade 8s ease-in-out infinite;
            z-index: 5;
            text-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
        }

        @keyframes motivationFade {
            0%, 100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            10%, 90% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-header {
                font-size: 24px;
                padding: 25px 20px;
            }

            .header-icon i {
                font-size: 45px;
            }

            .login-body {
                padding: 30px 25px;
            }

            .gym-icon {
                font-size: 30px;
            }

            .motivation-text {
                font-size: 14px;
            }
        }

        /* Power Lines Animation */
        .power-lines {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .power-line {
            position: absolute;
            width: 2px;
            height: 100%;
            background: linear-gradient(to bottom, transparent, var(--power-red), transparent);
            opacity: 0.2;
            animation: powerLineMove 3s linear infinite;
        }

        .power-line:nth-child(1) {
            left: 20%;
            animation-delay: 0s;
        }

        .power-line:nth-child(2) {
            left: 50%;
            animation-delay: 1s;
        }

        .power-line:nth-child(3) {
            left: 80%;
            animation-delay: 2s;
        }

        @keyframes powerLineMove {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(100%);
            }
        }
    </style>