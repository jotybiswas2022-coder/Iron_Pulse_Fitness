<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg shadow-sm py-2 gym-navbar">
    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center fw-bold fs-5 text-light" href="/">
            <i class="bi bi-lightning-charge-fill me-2 text-danger"></i>
            <span>Iron Pulse Gym</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarTopNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Top Nav Links -->
        <div class="collapse navbar-collapse show" id="navbarTopNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link gym-nav-link {{ request()->is('/') ? 'active-link' : '' }}" href="/">
                        <i class="bi bi-house-door-fill me-1"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link gym-nav-link {{ request()->is('orders') ? 'active-link' : '' }}" href="/orders">
                        <i class="bi bi-bag-check-fill me-1"></i> Ordered Packs
                    </a>
                </li>

                @auth
                    @if(auth()->user()->is_admin == 1)
                        <li class="nav-item">
                            <a class="nav-link gym-nav-link {{ request()->is('admin') ? 'active-link' : '' }}" href="/admin">
                                <i class="bi bi-speedometer2 me-1"></i> Admin
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>

                @else
                    <li class="nav-item">
                        <a class="nav-link gym-nav-link {{ request()->is('login') ? 'active-link' : '' }}" href="/login">
                            <i class="bi bi-person-circle me-1"></i> Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn join-btn text-white" href="/register">
                            <i class="bi bi-person-plus-fill me-1"></i> Join Now
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>


<!-- ================= BOTTOM SEARCH + CART BAR ================= -->
<div class="bottom-gym-bar py-2 shadow-sm">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">

        <!-- SEARCH (fixed from working version) -->
        <form action="{{ url('/search') }}" method="GET" class="flex-grow-1">
            <div class="input-group gym-input-group">

                <input type="text"
                       name="q"
                       class="form-control gym-search-input"
                       placeholder="Search packs..."
                       required>

                <button class="btn gym-search-btn px-3" type="submit">
                    <i class="bi bi-search"></i>
                </button>

            </div>
        </form>

        <!-- CART (optional safe version) -->
        <a href="{{ url('/cart') }}" class="cart-link position-relative">
            <i class="bi bi-cart3"></i>

            @if(function_exists('cart'))
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge">
                    {{ cart() }}
                </span>
            @endif
        </a>

    </div>
</div>

<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active link on scroll
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section[id]');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.gym-nav-link').forEach(link => {
                link.classList.remove('active-link');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active-link');
                }
            });
        });

        // Counter animation for stats
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.textContent);
                        const increment = target / 50;
                        let current = 0;
                        
                        const updateCounter = () => {
                            if (current < target) {
                                current += increment;
                                counter.textContent = Math.floor(current) + '+';
                                setTimeout(updateCounter, 30);
                            } else {
                                counter.textContent = target + '+';
                            }
                        };
                        updateCounter();
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #121212;
            color: #FFFFFF;
            overflow-x: hidden;
        }

        /* ================= NAVBAR ================= */
        .gym-navbar {
            background: linear-gradient(180deg, #0F0F0F 0%, #121212 100%);
            border-bottom: 2px solid rgba(229, 9, 20, 0.3);
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 4px 20px rgba(229, 9, 20, 0.2);
            isolation: isolate;
        }

        /* Brand */
        .navbar-brand {
            gap: 10px;
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 2px;
        }

        .navbar-brand i {
            font-size: 2rem;
            color: #E50914;
            animation: pulse-icon 2s ease-in-out infinite;
        }

        @keyframes pulse-icon {
            0%, 100% { 
                transform: scale(1);
                filter: drop-shadow(0 0 8px rgba(229, 9, 20, 0.6));
            }
            50% { 
                transform: scale(1.1);
                filter: drop-shadow(0 0 16px rgba(229, 9, 20, 0.9));
            }
        }

        .navbar-brand span {
            background: linear-gradient(135deg, #FFFFFF 30%, #E50914 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Toggler */
        .navbar-toggler {
            border: 2px solid #E50914;
            border-radius: 8px;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(229, 9, 20, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Top links */
        .navbar .gym-nav-link {
            font-weight: 600;
            font-size: 1.1rem;
            color: #FFFFFF !important;
            transition: all .3s ease;
            padding: 10px 16px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .navbar .gym-nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #E50914, #FF3B3B);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar .gym-nav-link:hover {
            color: #FF3B3B;
            background: rgba(229, 9, 20, 0.1);
            transform: translateY(-2px);
        }

        .navbar .gym-nav-link:hover::before {
            width: 80%;
        }

        /* Active */
        .navbar .gym-nav-link.active-link {
            color: #E50914;
            background: rgba(229, 9, 20, 0.15);
        }

        .navbar .gym-nav-link.active-link::before {
            width: 80%;
        }

        /* Join Now Button */
        .join-btn {
            background: linear-gradient(135deg, #E50914, #FF3B3B);
            color: #fff;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px 24px;
            border: none;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
            transition: all 0.3s ease;
            animation: glow-pulse 2s ease-in-out infinite;
        }

        @keyframes glow-pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(229, 9, 20, 0.8);
            }
        }

        .join-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.6);
        }

        /* ================= BOTTOM SEARCH BAR ================= */
        .bottom-gym-bar {
            background: linear-gradient(180deg, #0F0F0F 0%, #121212 100%);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(229, 9, 20, 0.2);
            border-bottom: 1px solid rgba(229, 9, 20, 0.2);
            position: sticky;
            top: 76px;
            z-index: 1040;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            isolation: isolate;
        }

        @media (min-width: 992px) {
            #navbarTopNav {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .gym-navbar .navbar-nav {
                display: flex !important;
            }
        }

        /* Search */
        .gym-input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.2);
        }

        .gym-search-input {
            background: #0F0F0F;
            border: 2px solid rgba(229, 9, 20, 0.3);
            color: #FFFFFF;
            border-right: none;
            font-weight: 500;
        }

        .gym-search-input:focus {
            background: #0F0F0F;
            border-color: #E50914;
            color: #FFFFFF;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        }

        .gym-search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .gym-search-btn {
            background: linear-gradient(135deg, #E50914, #FF3B3B);
            border: 2px solid #E50914;
            border-left: none;
            color: #fff;
            transition: all 0.3s ease;
        }

        .gym-search-btn:hover {
            background: linear-gradient(135deg, #FF3B3B, #E50914);
            transform: scale(1.05);
        }

        /* Quick Links */
        .quick-link {
            color: #FFFFFF;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(229, 9, 20, 0.1);
            border: 1px solid rgba(229, 9, 20, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .quick-link:hover {
            background: rgba(229, 9, 20, 0.2);
            border-color: #E50914;
            color: #FF3B3B;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
        }

        /* ================= HERO SECTION ================= */
        .hero-section {
            background: linear-gradient(135deg, #0F0F0F 0%, #1a0000 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(229, 9, 20, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 59, 59, 0.1) 0%, transparent 50%);
            animation: pulse-bg 4s ease-in-out infinite;
        }

        @keyframes pulse-bg {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 5rem;
            font-weight: 700;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #FFFFFF 0%, #E50914 50%, #FF3B3B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: title-glow 3s ease-in-out infinite;
            line-height: 1.2;
        }

        @keyframes title-glow {
            0%, 100% {
                filter: drop-shadow(0 0 10px rgba(229, 9, 20, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 30px rgba(229, 9, 20, 0.9));
            }
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 20px;
            font-weight: 500;
        }

        .cta-buttons {
            margin-top: 40px;
            gap: 20px;
        }

        .btn-primary-gym {
            background: linear-gradient(135deg, #E50914, #FF3B3B);
            color: #fff;
            padding: 15px 40px;
            font-size: 1.3rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.4);
        }

        .btn-primary-gym:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 35px rgba(229, 9, 20, 0.6);
        }

        .btn-secondary-gym {
            background: transparent;
            color: #fff;
            padding: 15px 40px;
            font-size: 1.3rem;
            font-weight: 700;
            border: 2px solid #E50914;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-secondary-gym:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: #FF3B3B;
            transform: translateY(-5px);
        }

        /* Animated Icons */
        .hero-icon {
            position: absolute;
            font-size: 3rem;
            color: rgba(229, 9, 20, 0.2);
            animation: float 6s ease-in-out infinite;
        }

        .hero-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .hero-icon:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .hero-icon:nth-child(3) {
            bottom: 20%;
            left: 15%;
            animation-delay: 2s;
        }

        .hero-icon:nth-child(4) {
            bottom: 15%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(10deg);
            }
        }

        /* ================= STATS SECTION ================= */
        .stats-section {
            background: #0F0F0F;
            padding: 60px 0;
            border-top: 2px solid rgba(229, 9, 20, 0.3);
            border-bottom: 2px solid rgba(229, 9, 20, 0.3);
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.1) 0%, rgba(18, 18, 18, 0.8) 100%);
            border-radius: 16px;
            border: 2px solid rgba(229, 9, 20, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            border-color: #E50914;
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.4);
        }

        .stat-icon {
            font-size: 3.5rem;
            color: #E50914;
            margin-bottom: 15px;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .stat-number {
            font-family: 'Bebas Neue', cursive;
            font-size: 3.5rem;
            color: #FF3B3B;
            font-weight: 700;
            line-height: 1;
        }

        .stat-label {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            margin-top: 10px;
        }

        /* ================= FEATURES SECTION ================= */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(180deg, #121212 0%, #0F0F0F 100%);
        }

        .section-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 3.5rem;
            text-align: center;
            color: #FFFFFF;
            margin-bottom: 50px;
            letter-spacing: 3px;
        }

        .section-title span {
            color: #E50914;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.9) 0%, rgba(18, 18, 18, 0.9) 100%);
            border: 2px solid rgba(229, 9, 20, 0.2);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.1), transparent);
            transition: all 0.6s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            border-color: #E50914;
            box-shadow: 0 15px 40px rgba(229, 9, 20, 0.4);
        }

        .feature-icon {
            font-size: 4rem;
            color: #E50914;
            margin-bottom: 20px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.2) rotate(10deg);
            filter: drop-shadow(0 0 20px rgba(229, 9, 20, 0.8));
        }

        .feature-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 15px;
        }

        .feature-description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        /* ================= MOBILE RESPONSIVE ================= */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: #0F0F0F;
                border-radius: 12px;
                margin-top: 15px;
                padding: 20px;
                border: 2px solid rgba(229, 9, 20, 0.3);
            }

            #navbarTopNav:not(.show) {
                display: none !important;
            }

            .join-btn {
                width: 100%;
                margin-top: 10px;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .btn-primary-gym, .btn-secondary-gym {
                font-size: 1.1rem;
                padding: 12px 30px;
            }

            .section-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .bottom-gym-bar .container {
                gap: 15px;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn-primary-gym, .btn-secondary-gym {
                width: 100%;
            }
        }
    </style>
