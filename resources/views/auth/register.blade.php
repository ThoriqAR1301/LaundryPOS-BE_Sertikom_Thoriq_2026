<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — LaundryPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bubble { position: absolute; border-radius: 50%; opacity: 0.12; animation: float linear infinite; }
        @keyframes float { 0% { transform: translateY(100vh) scale(0); } 100% { transform: translateY(-100px) scale(1); } }
        .glass-input { width:100%; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2); border-radius:0.75rem; padding:0.75rem 1rem 0.75rem 2.75rem; color:#fff; font-size:0.875rem; outline:none; transition:all 0.2s; }
        .glass-input::placeholder { color: rgba(147,197,253,0.7); }
        .glass-input:focus { background:rgba(255,255,255,0.12); border-color:rgba(34,211,238,0.6); box-shadow: 0 0 0 3px rgba(34,211,238,0.15); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-800 via-blue-900 to-cyan-900 flex justify-center p-4 py-8 relative overflow-y-auto">

    @for($i = 0; $i < 8; $i++)
    <div class="bubble bg-white"
         style="width:{{ rand(20,80) }}px; height:{{ rand(20,80) }}px; left:{{ rand(0,100) }}%; animation-duration:{{ rand(8,20) }}s; animation-delay:{{ rand(0,10) }}s;"></div>
    @endfor

    <div class="w-full max-w-md relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl shadow-blue-500/30" style="background: linear-gradient(135deg, #60a5fa, #22d3ee)">
                <i class="fas fa-shirt text-white text-3xl"></i>
            </div>
            <h1 class="text-white text-3xl font-bold">LaundryPOS</h1>
            <p class="text-blue-200 text-sm mt-1">Buat Akun Admin Baru</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            <h2 class="text-white text-xl font-bold mb-1">Daftar Admin 🚀</h2>
            <p class="text-blue-200 text-sm mb-6">Isi Data Berikut Untuk Membuat Akun</p>

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-400/30 text-red-300 px-4 py-3 rounded-xl mb-4 text-sm">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2 mb-1"><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-blue-200 text-sm font-semibold mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" class="glass-input">
                    </div>
                </div>
                <div>
                    <label class="block text-blue-200 text-sm font-semibold mb-1.5">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="glass-input">
                    </div>
                </div>
                <div>
                    <label class="block text-blue-200 text-sm font-semibold mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="password" name="password" id="password" placeholder="Minimal 8 Karakter" class="glass-input" style="padding-right: 2.75rem">
                        <button type="button" onclick="togglePassword('password','eye-icon-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-icon-1"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-blue-200 text-sm font-semibold mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="password" name="password_confirmation" id="password2" placeholder="Ulangi Password" class="glass-input" style="padding-right: 2.75rem">
                        <button type="button" onclick="togglePassword('password2','eye-icon-2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-icon-2"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition-all duration-200 shadow-lg flex items-center justify-center gap-2 mt-2" style="background: linear-gradient(to right, #3b82f6, #06b6d4)">
                    <i class="fas fa-user-plus"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-white/10">
                <p class="text-blue-200 text-sm">Sudah Punya Akun?
                    <a href="{{ route('login') }}" class="text-cyan-400 font-semibold hover:text-cyan-300 transition-colors">Masuk Di Sini</a>
                </p>
            </div>
        </div>

        <p class="text-center text-blue-300 text-xs mt-6">© {{ date('Y') }} LaundryPOS. All Rights Reserved</p>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>