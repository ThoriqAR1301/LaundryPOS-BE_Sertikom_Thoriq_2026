<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <title>Login — LaundryPOS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .bubble {
            position: absolute; border-radius: 50%; opacity: 0.10;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(110vh) scale(0.3); opacity: 0; }
            10% { opacity: 0.10; }
            90% { opacity: 0.10; }
            100% { transform: translateY(-120px) scale(1); opacity: 0; }
        }

        .glass-input {
            width: 100%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 0.875rem;
            padding: 0.875rem 1rem 0.875rem 2.875rem;
            color: #fff; font-size: 0.875rem; outline: none;
            transition: all 0.25s;
        }
        .glass-input::placeholder { color: rgba(147,197,253,0.6); }
        .glass-input:focus {
            background: rgba(255,255,255,0.13);
            border-color: rgba(34,211,238,0.7);
            box-shadow: 0 0 0 3px rgba(34,211,238,0.15);
        }

        #loading-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.65);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: none; align-items: center; justify-content: center;
            flex-direction: column; gap: 1rem;
        }
        #loading-overlay.active { display: flex; animation: fadeIn 0.2s ease-out; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

        .spinner-ring {
            width: 56px; height: 56px; border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.12);
            border-top-color: #38bdf8; border-right-color: #818cf8;
            animation: spin 0.75s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .spinner-text {
            color: #e2e8f0; font-size: 0.8rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            animation: pulseTxt 1.2s ease-in-out infinite;
        }
        @keyframes pulseTxt { 0%,100% { opacity:0.5; } 50% { opacity:1; } }

        #toast-container {
            position: fixed; top: 1.25rem; right: 1.25rem; z-index: 10000;
            display: flex; flex-direction: column; gap: 0.625rem; pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex; align-items: flex-start; gap: 0.875rem;
            padding: 0.875rem 1.125rem; border-radius: 1rem;
            min-width: 300px; max-width: 380px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            animation: toastIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
            position: relative; overflow: hidden;
        }
        .toast.toast-hide { animation: toastOut 0.3s ease-in forwards; }
        @keyframes toastIn  { from { opacity:0; transform:translateX(120%) scale(0.92); } to { opacity:1; transform:translateX(0) scale(1); } }
        @keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(120%) scale(0.9); } }
        .toast-progress { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 1rem 1rem; animation:shrink linear forwards; }
        @keyframes shrink { from { width:100%; } to { width:0%; } }

        .toast-success { background:linear-gradient(135deg,rgba(6,78,59,0.95),rgba(5,150,105,0.92)); border-color:rgba(16,185,129,0.4); }
        .toast-success .toast-progress { background:#34d399; }
        .toast-error { background:linear-gradient(135deg,rgba(127,29,29,0.95),rgba(185,28,28,0.92)); border-color:rgba(239,68,68,0.4); }
        .toast-error .toast-progress { background:#f87171; }
        .toast-warning { background:linear-gradient(135deg,rgba(120,53,15,0.95),rgba(180,83,9,0.92)); border-color:rgba(245,158,11,0.4); }
        .toast-warning .toast-progress { background:#fbbf24; }

        .toast-icon { width:36px; height:36px; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.9rem; }
        .toast-success .toast-icon { background:rgba(16,185,129,0.25); color:#6ee7b7; }
        .toast-error .toast-icon { background:rgba(239,68,68,0.25);  color:#fca5a5; }
        .toast-warning .toast-icon { background:rgba(245,158,11,0.25); color:#fde68a; }
        .toast-body { flex:1; }
        .toast-title { font-weight:700; font-size:0.8rem; color:#f1f5f9; margin-bottom:0.1rem; }
        .toast-message { font-size:0.78rem; color:#cbd5e1; line-height:1.45; }
        .toast-close { background:none; border:none; color:#94a3b8; cursor:pointer; padding:0.1rem; flex-shrink:0; transition:color 0.15s; font-size:0.75rem; margin-top:0.1rem; }
        .toast-close:hover { color:#f1f5f9; }

        .btn-login {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            transition: all 0.25s;
        }
        .btn-login:hover {
            background: linear-gradient(to right, #2563eb, #0891b2);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        }
        .btn-login:active { transform: translateY(0); }

        .login-card {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.20);
            border-radius: 1.75rem;
            padding: 2.25rem;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.12);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden"
      style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0c4a6e 70%, #134e4a 100%)">

    <div id="loading-overlay">
        <div class="spinner-ring"></div>
        <p class="spinner-text">Masuk...</p>
    </div>

    <div id="toast-container"></div>

    @for($i = 0; $i < 10; $i++)
    <div class="bubble bg-cyan-400" style="width:{{ rand(15,70) }}px;height:{{ rand(15,70) }}px;left:{{ rand(0,100) }}%;animation-duration:{{ rand(10,22) }}s;animation-delay:{{ rand(0,12) }}s;"></div>
    @endfor

    <div class="w-full max-w-md relative z-10">


        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl" style="background:linear-gradient(135deg,#3b82f6,#06b6d4);box-shadow:0 16px 40px rgba(59,130,246,0.4)">
                <i class="fas fa-shirt text-white text-3xl"></i>
            </div>
            <h1 class="text-white text-3xl font-bold tracking-tight">LaundryPOS</h1>
            <p class="text-blue-300 text-sm mt-1.5">Sistem Manajemen Laundry Modern</p>
        </div>

        <div class="login-card">
            <h2 class="text-white text-xl font-bold mb-1">Selamat Datang! 👋</h2>
            <p class="text-blue-200 text-sm mb-7">Masuk Ke Panel Admin Anda</p>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="login-form">
                @csrf

                <div>
                    <label class="block text-blue-200 text-xs font-semibold mb-2 uppercase tracking-wider">
                        <i class="fas fa-envelope mr-1 opacity-70"></i> Email
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@laundry.com" class="glass-input" required autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-blue-200 text-xs font-semibold mb-2 uppercase tracking-wider">
                        <i class="fas fa-lock mr-1 opacity-70"></i> Password
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="glass-input" style="padding-right:3rem" required>
                        <button type="button" onclick="togglePwd('password','eye-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-1"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded accent-cyan-400 cursor-pointer">
                    <label for="remember" class="text-blue-200 text-sm cursor-pointer select-none">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit" class="btn-login w-full py-3.5 rounded-xl font-bold text-sm text-white shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-white/10">
                <p class="text-blue-200 text-sm">
                    Belum Punya Akun?
                    <a href="{{ route('register') }}" class="text-cyan-400 font-semibold hover:text-cyan-300 transition-colors">
                        Daftar Di Sini
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-blue-400 text-xs mt-6 opacity-60">
            © {{ date('Y') }} LaundryPOS. All Rights Reserved
        </p>
    </div>

    <script>
        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !hidden);
            icon.classList.toggle('fa-eye-slash', hidden);
        }

        window.addEventListener('pageshow', () => {
            document.getElementById('loading-overlay').classList.remove('active');
        });
        document.getElementById('login-form').addEventListener('submit', () => {
            document.getElementById('loading-overlay').classList.add('active');
        });

        const LaundryToast = (() => {
            const container = document.getElementById('toast-container');
            const DURATION = 4500;
            const iconMap = { success:'fas fa-check', error:'fas fa-times', warning:'fas fa-exclamation' };
            const titleMap = { success:'Berhasil!', error:'Gagal!', warning:'Perhatian!' };

            function show(type, title, message, duration) {
                const dur = duration || DURATION;
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <div class="toast-icon"><i class="${iconMap[type]}"></i></div>
                    <div class="toast-body">
                        <p class="toast-title">${title || titleMap[type]}</p>
                        ${message ? `<p class="toast-message">${message}</p>` : ''}
                    </div>
                    <button class="toast-close" onclick="LaundryToast.dismiss(this.closest('.toast'))">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="toast-progress" style="animation-duration:${dur}ms"></div>
                `;
                container.appendChild(toast);
                setTimeout(() => LaundryToast.dismiss(toast), dur);
            }

            function dismiss(toast) {
                if (!toast || toast.classList.contains('toast-hide')) return;
                toast.classList.add('toast-hide');
                setTimeout(() => toast.remove(), 300);
            }

            return {
                success : (t, m, d) => show('success', t, m, d),
                error   : (t, m, d) => show('error',   t, m, d),
                warning : (t, m, d) => show('warning', t, m, d),
                dismiss,
            };
        })();

        @if(session('success'))
            LaundryToast.success('Berhasil!', @json(session('success')));
        @endif
        @if(session('error'))
            LaundryToast.error('Gagal!', @json(session('error')));
        @endif
        @if($errors->any())
            LaundryToast.error('Login Gagal!', @json($errors->first()));
        @endif
    </script>
</body>
</html>