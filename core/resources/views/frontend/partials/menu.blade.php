<nav class="navbar navbar-expand-lg shadow-sm py-2 dark-navbar">
    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center fw-bold fs-5 text-light" href="/">
            <i class="bi bi-phone-vibrate-fill me-2"></i>
            <span>Phone Store</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarTopNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Top Nav Links -->
        <div class="collapse navbar-collapse" id="navbarTopNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link top-nav-link {{ request()->is('/') ? 'active-link' : '' }}" href="/">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link top-nav-link {{ request()->is('orders') ? 'active-link' : '' }}" href="/orders">
                        <i class="bi bi-bag-check me-1"></i> Orders
                    </a>
                </li>

                @auth
                    @if(auth()->user()->is_admin == 1)
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->is('admin') ? 'active-link' : '' }}" href="/admin">
                                <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="btn-logout w-100 text-start">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link top-nav-link {{ request()->is('login') ? 'active-link' : '' }}" href="/login">
                            <i class="bi bi-person-circle me-1"></i> Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link signup-btn text-center" href="/register">
                            <i class="bi bi-person-plus me-1"></i> Signup
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

<!-- ================= BOTTOM SEARCH + CART BAR ================= -->
<div class="bottom-glass-bar py-2 shadow-sm">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">

        <!-- Search -->
        <form action="{{ url('/search') }}" method="GET" class="search-form flex-grow-1">
            <div class="input-group">
                <input type="text" name="q" class="form-control glass-input"
                       placeholder="Search product name or category..." required>
                <button class="btn search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- Cart -->
        <a href="{{ url('/cart') }}" class="cart-link">
            <i class="bi bi-cart3"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge">
                {{ cart() }}
            </span>
        </a>

    </div>
</div>

<style>
    /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #0a0f1e;
    color: #e2e8f0;
}

/* ================= NAVBAR ================= */
.dark-navbar {
    background: linear-gradient(180deg, #0f172a 0%, #0d1424 100%);
    border-bottom: 1px solid rgba(59, 130, 246, 0.08);
    position: sticky;
    top: 0;
    z-index: 1050;
}

/* Brand */
.navbar-brand {
    gap: 6px;
}

.navbar-brand i {
    font-size: 1.5rem;
    color: #3b82f6 !important;
    filter: drop-shadow(0 0 6px rgba(59, 130, 246, 0.5));
}

.navbar-brand span {
    background: linear-gradient(135deg, #ffffff 30%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Toggler */
.navbar-toggler {
    border-radius: 8px;
}

/* Top links */
.navbar .top-nav-link {
    font-weight: 500;
    font-size: 0.9rem;
    color: #94a3b8;
    transition: all .25s ease;
    padding: 8px 12px;
    border-radius: 8px;
}

.navbar .top-nav-link:hover {
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.08);
}

/* Active */
.navbar .top-nav-link.active-link {
    color: #3b82f6 !important;
    background: rgba(59, 130, 246, 0.06);
}

/* Logout */
.btn-logout {
    color: #f87171 !important;
    font-weight: 600;
    border: none;
    background: transparent;
    border-radius: 8px;
    padding: 8px 12px;
}

/* Signup */
.signup-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff !important;
    font-weight: 600;
    border-radius: 10px !important;
    padding: 8px 18px !important;
}

/* ================= BOTTOM BAR ================= */
.bottom-glass-bar {
    background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
    backdrop-filter: blur(16px);
    border-top: 1px solid rgba(59, 130, 246, 0.06);
    position: sticky;
    top: 56px;
    z-index: 1040;
}

/* Search */
.input-group {
    border-radius: 12px;
    overflow: hidden;
}

.glass-input {
    background: #1e293b !important;
    border: 1px solid rgba(59, 130, 246, 0.12) !important;
    color: #e5e7eb !important;
    border-right: none !important;
}

.search-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    border-left: none !important;
    color: #fff;
}

/* Cart */
.cart-link {
    color: #cbd5e1;
    font-size: 1.5rem;
    position: relative;
}

.cart-badge {
    font-size: 0.65rem !important;
    font-weight: 700;
    padding: 3px 6px;
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
}

/* ================= MOBILE ================= */
@media (max-width: 991px) {

    .navbar-collapse {
        background: #0f172a;
        border-radius: 12px;
        margin-top: 10px;
        padding: 16px;
        border: 1px solid rgba(59, 130, 246, 0.08);
    }

    .signup-btn {
        display: block;
        width: 100%;
        margin-top: 6px;
    }

    .btn-logout {
        width: 100%;
        text-align: left;
    }
}

@media (max-width: 768px) {

    .bottom-glass-bar .container {
        gap: 10px;
    }

    .search-form {
        width: 100%;
        order: 2;
    }

    .cart-link {
        order: 1;
    }
}
</style>