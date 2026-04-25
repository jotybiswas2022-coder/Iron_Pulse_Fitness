<div class="login-container">

    <!-- Background Grid -->
    <div class="grid-bg"></div>

    <!-- Glow Orb -->
    <div class="glow-orb"></div>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 d-flex justify-content-center">
                <div class="card login-card">

                    <!-- HEADER -->
                    <div class="card-header login-header text-center">
                        <span class="icon-lock">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </span>
                        {{ __('Login') }}
                    </div>

                    <div class="card-body login-body">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- EMAIL -->
                            <div class="form-group-custom">
                                <label for="email" class="login-label pb-2">
                                    {{ __('Email Address') }}
                                </label>

                                <input id="email" type="email"
                                       class="form-control login-input @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@example.com"
                                       required autocomplete="off" autofocus>

                                @error('email')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group-custom">
                                <label for="password" class="login-label">
                                    {{ __('Password') }}
                                </label>

                                <input id="password" type="password"
                                       class="form-control login-input @error('password') is-invalid @enderror"
                                       name="password"
                                       placeholder="••••••••"
                                       required autocomplete="new-password">

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
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <!-- BUTTON -->
                            <button type="submit" class="login-btn mt-3">
                                Login
                            </button>

                            @if (Route::has('password.request'))
                            <div class="text-end">
                                <a class="login-link" href="{{ route('password.request') }}">
                                    Forgot Password?
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
                                Sign Up
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

    /* ================= ROOT ================= */
    :root {
        --primary: #0f172a;
        --card: #111c2e;
        --accent: #3b82f6;
        --accent-soft: rgba(59,130,246,.15);
        --text: #e2e8f0;
        --muted: #94a3b8;
    }

    /* ================= RESET ================= */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        overflow-y: auto;
        font-family: 'Inter', sans-serif;
        background: var(--primary);
        color: var(--text);
    }

    /* ================= CONTAINER ================= */
    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        padding: 40px 16px;
        overflow: hidden;
    }

    /* floating blobs */
    .login-container::before,
    .login-container::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .login-container::before {
        top: -180px;
        right: -180px;
        width: 520px;
        height: 520px;
        background: radial-gradient(circle, rgba(59,130,246,.12), transparent 70%);
        animation: blobFloat 12s ease-in-out infinite alternate;
    }

    .login-container::after {
        bottom: -180px;
        left: -180px;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(59,130,246,.08), transparent 70%);
        animation: blobFloat 10s ease-in-out infinite alternate-reverse;
    }

    @keyframes blobFloat {
        0%   { transform: translate(0, 0) scale(1); }
        50%  { transform: translate(30px, -20px) scale(1.05); }
        100% { transform: translate(-20px, 15px) scale(0.97); }
    }

    /* grid */
    .grid-bg {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(59,130,246,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(59,130,246,.04) 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: 0;
        animation: gridShift 25s linear infinite;
    }

    @keyframes gridShift {
        0%   { background-position: 0 0; }
        100% { background-position: 50px 50px; }
    }

    /* orb */
    .glow-orb {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(59,130,246,.10), transparent 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        filter: blur(12px);
        animation: orbPulse 6s ease-in-out infinite;
    }

    @keyframes orbPulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        50%      { transform: translate(-50%, -50%) scale(1.15); opacity: .7; }
    }

    /* ================= PARTICLES ================= */
    .particles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        bottom: -10px;
        background: var(--accent);
        border-radius: 50%;
        animation: particleRise linear infinite;
        box-shadow: 0 0 6px rgba(59,130,246,.5);
    }

    @keyframes particleRise {
        0%   { transform: translateY(0) scale(1); opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { transform: translateY(-110vh) scale(0.3); opacity: 0; }
    }

    /* ============ CARD WIDTH UPDATE ============ */
    .login-card {
        max-width: 660px; /* widened card */
        border-radius: 22px;
        background: linear-gradient(145deg, #162338, #0f1b2d);
        border: 1px solid rgba(59,130,246,.18);
        box-shadow:
            0 35px 80px rgba(0,0,0,.65),
            0 0 60px rgba(59,130,246,.12);
        position: relative;
        z-index: 5;
        overflow: hidden;
        animation: fadeUp .6s ease;
    }

    /* top accent line */
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        animation: accentSweep 3s ease-in-out infinite;
    }

    @keyframes accentSweep {
        0%   { opacity: .5; }
        50%  { opacity: 1; }
        100% { opacity: .5; }
    }

    /* ================= HEADER ================= */
    .login-header {
        padding: 28px;
        font-size: 26px;
        font-weight: 700;
        color: #f1f5f9;
        border-bottom: 1px solid rgba(59,130,246,.12);
        background: rgba(59,130,246,.05);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        letter-spacing: -0.3px;
    }

    .icon-lock {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--accent-soft);
        border: 1px solid rgba(59,130,246,.25);
        transition: .3s;
    }

    .icon-lock:hover {
        background: rgba(59,130,246,.25);
        transform: rotate(-8deg) scale(1.08);
    }

    .icon-lock svg {
        width: 20px;
        color: var(--accent);
    }

    /* ================= BODY ================= */
    .login-body {
        padding: 32px;
    }

    .login-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 500;
        padding-bottom: 6px;
    }

    /* input */
    .login-input {
        padding: 14px 16px;
        background: #0b1220;
        border: 1.5px solid rgba(59,130,246,.22);
        border-radius: 12px;
        color: var(--text);
        width: 100%;
        font-size: 15px;
        transition: .25s;
        font-family: 'Inter', sans-serif;
    }

    .form-group-custom{
        margin-bottom:20px;
    }

    .login-label{
        display:block;
        margin-bottom: 6px;
    }

    .login-input::placeholder {
        color: #475569;
    }

    .login-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59,130,246,.18);
        background: #0a1324;
    }

    /* checkbox */
    .form-check-input {
        background-color: #0b1220;
        border: 1.5px solid rgba(59,130,246,.3);
        border-radius: 4px;
        width: 18px;
        height: 18px;
        cursor: pointer;
        transition: .2s;
    }

    .form-check-input:checked {
        background-color: var(--accent);
        border-color: var(--accent);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(59,130,246,.2);
        border-color: var(--accent);
    }

    /* button */
    .login-btn {
        padding: 14px;
        font-weight: 600;
        font-size: 16px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        border: none;
        border-radius: 12px;
        width: 100%;
        cursor: pointer;
        transition: .25s;
        font-family: 'Inter', sans-serif;
        position: relative;
        overflow: hidden;
    }

    .login-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(255,255,255,.12), transparent);
        transform: translateX(-100%);
        transition: .5s;
    }

    .login-btn:hover::after {
        transform: translateX(100%);
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(59,130,246,.45);
    }

    .login-btn:active {
        transform: translateY(0);
        box-shadow: 0 5px 15px rgba(59,130,246,.3);
    }

    /* links */
    .login-link {
        color: #60a5fa;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        margin-top: 12px;
        transition: .2s;
    }

    .login-link:hover {
        color: #93c5fd;
        text-decoration: underline;
    }

    /* divider */
    .divider {
        display: flex;
        align-items: center;
        margin: 24px 0;
        gap: 10px;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(59,130,246,.15);
    }

    .divider span {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 1px;
    }

    /* signup */
    .signup-text {
        color: #64748b;
        font-size: 14px;
    }

    .signup-btn {
        color: #60a5fa;
        border: 1px solid rgba(59,130,246,.35);
        border-radius: 10px;
        padding: 9px 22px;
        font-weight: 600;
        text-decoration: none;
        transition: .25s;
        display: inline-block;
    }

    .signup-btn:hover {
        background: rgba(59,130,246,.12);
        border-color: var(--accent);
        color: #93c5fd;
        transform: translateY(-1px);
    }

    .login-remember {
        color: var(--muted);
        font-size: 14px;
        cursor: pointer;
    }

    /* validation */
    .invalid-feedback {
        color: #f87171;
        font-size: 13px;
        margin-top: 6px;
    }

    /* animation */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(25px) scale(.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .login-header { font-size: 24px; padding: 22px; }
        .login-body { padding: 24px; }
    }

    @media (max-width: 576px) {
        .login-container { padding: 24px 10px; }
        .login-card { border-radius: 18px; }
        .login-header { font-size: 22px; padding: 18px; }
        .login-body { padding: 20px; }
        .glow-orb { width: 260px; height: 260px; }
    }

    /* ===== Scrollbar ===== */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--primary); }
    ::-webkit-scrollbar-thumb { background: rgba(59,130,246,.3); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(59,130,246,.5); }

    /* ===== form spacing fix ===== */
.form-group-custom{
    margin-bottom:18px;
}

/* ===== checkbox spacing ===== */
.custom-check{
    margin-top:6px;
    margin-bottom:6px;
}

/* ===== better mobile stacking ===== */
@media (max-width: 576px){

    .form-group-custom label{
        display:block;
        margin-bottom:6px;
    }

    .login-btn{
        font-size:15px;
        padding:13px;
    }

    .login-card{
        max-width:100%;
    }

    .container{
        padding-left:8px;
        padding-right:8px;
    }
}

/* prevent horizontal scroll */
html, body{
    overflow-x:hidden;
}

    </style>

<script>
// Particle Generation
const particlesContainer = document.getElementById('particles');
if (particlesContainer) {
    for (let i=0;i<30;i++){
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.left = Math.random()*100+'%';
        particle.style.animationDuration = (Math.random()*8+6)+'s';
        particle.style.animationDelay = (Math.random()*5)+'s';
        particle.style.opacity = Math.random()*0.5+0.2;
        const size = Math.random()*3+1;
        particle.style.width = size+'px';
        particle.style.height = size+'px';
        particlesContainer.appendChild(particle);
    }
}

// Card subtle glow on mouse move
const card = document.querySelector('.login-card');
if(card){
    card.addEventListener('mousemove', e=>{
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.background = `radial-gradient(circle 200px at ${x}px ${y}px, rgba(59,130,246,0.06), transparent), linear-gradient(145deg, #1e293b, #162032)`;
    });
    card.addEventListener('mouseleave', ()=>{ card.style.background = 'linear-gradient(145deg, #1e293b, #162032)'; });
}

// Input focus effect
document.querySelectorAll('.login-input').forEach(input=>{
    input.addEventListener('focus', function(){ this.parentElement.style.transform='scale(1.01)'; this.parentElement.style.transition='transform 0.3s ease'; });
    input.addEventListener('blur', function(){ this.parentElement.style.transform='scale(1)'; });
});
</script>