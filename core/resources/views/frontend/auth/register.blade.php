<div class="login-container">
    <div class="particles" id="particles"></div>
    <div class="login-wrapper">
        <div class="card login-card">
            <div class="card-header login-header">
                <div class="header-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                        <line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                </div>
                Register
            </div>

            <div class="card-body login-body">
                <form method="POST" action="{{ route('register') }}" autocomplete="off">
                    @csrf

                    <div class="input-group-animated">
                        <label for="name" class="login-label">Name</label>
                        <input id="name" type="text" class="form-control login-input @error('name') is-invalid @enderror" name="name" placeholder="Enter your name" required autofocus autocomplete="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div><br>

                    <div class="input-group-animated">
                        <label for="email" class="login-label">Email Address</label>
                        <input id="email" type="email" class="form-control login-input @error('email') is-invalid @enderror" name="email" placeholder="you@example.com" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div><br>

                    <div class="input-group-animated">
                        <label for="password" class="login-label">Password</label>
                        <input id="password" type="password" class="form-control login-input @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div><br>

                    <div class="input-group-animated">
                        <label for="password-confirm" class="login-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control login-input" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                    </div><br>

                    <div class="input-group-animated mt-3 d-flex flex-column gap-3">
                        <button type="submit" class="btn login-btn">
                            {{ __('Register') }}
                        </button><br><br>
                        <a href="{{ route('login') }}" class="login-link">Already have an account? Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
body {
    font-family: 'Inter', sans-serif;
    background: #0f172a;
    margin:0;
    padding:0;
    overflow-x: hidden;
    overflow-y: hidden;
}
.login-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    padding: 20px;
}
.login-container::before {
    content:'';
    position:absolute;
    top:-50%; left:-50%;
    width:200%; height:200%;
    background: radial-gradient(ellipse at 20% 50%, rgba(59,130,246,0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(59,130,246,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(59,130,246,0.06) 0%, transparent 50%);
    animation: bgPulse 8s ease-in-out infinite alternate;
    z-index:0;
}
@keyframes bgPulse {0%{transform:scale(1) rotate(0deg);}100%{transform:scale(1.1) rotate(3deg);}}
.particles {
    position:absolute; top:0; left:0;
    width:100%; height:100%;
    pointer-events:none; z-index:0;
}
.particle {
    position:absolute; border-radius:50%;
    background: rgba(59,130,246,0.4);
    animation: floatUp linear infinite;
}
@keyframes floatUp {0%{transform:translateY(100vh) scale(0);opacity:0;}10%{opacity:1;}90%{opacity:1;}100%{transform:translateY(-10vh) scale(1);opacity:0;}}
.login-wrapper {
    width: 100%; max-width: 400px;
    z-index:1; padding-bottom: 40px;
}
.login-card {
    background: rgba(20,28,45,0.95);
    border-radius:36px;
    box-shadow:0 25px 60px rgba(0,0,0,0.6),0 0 40px rgba(59,130,246,0.2);
    backdrop-filter: blur(28px);
    border:1px solid rgba(59,130,246,0.2);
    padding-bottom: 28px;
    opacity:0; transform: translateY(20px);
    animation: cardEntry 0.8s forwards;
}
.login-header {
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color:#fff;
    text-align:center;
    font-size:26px;
    font-weight:700;
    padding:28px 20px;
    border-radius:36px 36px 0 0;
    border-bottom:1px solid rgba(59,130,246,0.25);
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:16px;
}
.header-icon {
    width:50px; height:50px;
    background: linear-gradient(135deg,#3b82f6,#2563eb);
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 12px 35px rgba(59,130,246,0.4);
    transition: all 0.3s ease;
}
.header-icon:hover { transform:scale(1.1); }
.header-icon svg { width:24px; height:24px; fill:none; stroke:white; stroke-width:2; }
.login-body { padding:28px 32px; display:flex; flex-direction:column; gap:20px; }
.login-label { font-weight:500; color:#94a3b8; font-size:14px; margin-bottom:6px; display:block; line-height:1.6; }
.login-input {
    border-radius:16px; padding:12px 16px; font-size:15px;
    border:1px solid rgba(59,130,246,0.25);
    background: rgba(15,23,42,0.55);
    color:#e2e8f0;
    width:100%; line-height:1.5;
    transition: all 0.3s ease;
}
.login-input:focus {
    border-color:#3b82f6;
    box-shadow:0 0 0 5px rgba(59,130,246,0.2),0 0 20px rgba(59,130,246,0.15);
    background: rgba(15,23,42,0.75);
    color:#f1f5f9;
    outline:none;
}
.login-btn {
    background: linear-gradient(135deg,#3b82f6,#2563eb);
    border:none; color:#fff;
    padding:14px; font-size:15px; border-radius:32px;
    font-weight:600; width:100%; transition:all 0.3s ease;
}
.login-btn:hover {
    transform:scale(1.03);
    box-shadow:0 12px 28px rgba(59,130,246,0.45);
}
.login-link {
    font-size:13px; color:#3b82f6; text-decoration:none;
    text-align:center; display:block;
}
.login-link::after {
    content:''; display:block; width:0; height:1px;
    background:#3b82f6; transition: width 0.3s ease; margin:2px auto 0;
}
.login-link:hover::after { width:65%; }
.input-group-animated {
    animation: inputSlide 0.6s forwards;
    opacity:0; transform:translateY(20px);
}
@keyframes inputSlide { to{opacity:1; transform:translateY(0);} }
@keyframes cardEntry { to{opacity:1; transform:translateY(0);} }
@media(max-width:576px){
    .login-wrapper { max-width:95%; padding-bottom: 30px; }
    .login-card{padding-bottom:28px;}
    .login-body{padding:16px 14px; gap:14px;}
    .login-input{padding:10px 12px; font-size:14px;}
    .login-btn{padding:10px; font-size:14px;}
    .login-header{font-size:20px;padding:16px 12px;}
}
</style>

<script>
const particlesContainer=document.getElementById('particles');
const isMobile=window.innerWidth<=576;
const particleCount=isMobile?12:35;

for(let i=0;i<particleCount;i++){
    const p=document.createElement('div');
    p.classList.add('particle');
    const size=Math.random()*5+3;
    p.style.width=size+'px';
    p.style.height=size+'px';
    p.style.left=Math.random()*100+'%';
    p.style.animationDuration=(Math.random()*6+6)+'s';
    p.style.animationDelay=(Math.random()*10)+'s';
    p.style.opacity=Math.random()*0.5+0.1;
    particlesContainer.appendChild(p);
}

document.querySelectorAll('.login-input').forEach(input=>{
    input.addEventListener('focus',function(){
        this.style.transform='scale(1.02)';
        this.style.transition='transform 0.3s ease';
    });
    input.addEventListener('blur',function(){
        this.style.transform='scale(1)';
    });
});
</script>