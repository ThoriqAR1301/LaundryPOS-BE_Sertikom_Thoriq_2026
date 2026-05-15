<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaundryPOS') — Admin Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
                        aqua: { 50:'#ecfeff',100:'#cffafe',200:'#a5f3fc',300:'#67e8f9',400:'#22d3ee',500:'#06b6d4',600:'#0891b2',700:'#0e7490',800:'#155e75',900:'#164e63' },
                        laundry: { 50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#0369a1',800:'#075985',900:'#0c4a6e' },
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-radius:0.75rem; color:#cbd5e1; font-size:0.875rem; font-weight:500; transition:all 0.2s; }
        .sidebar-link:hover { background:rgba(255,255,255,0.1); color:#fff; }
        .sidebar-link.active { background:rgba(255,255,255,0.15); color:#fff; font-weight:600; }
        .card { background:#fff; border-radius:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.06); border:1px solid #f1f5f9; padding:1.5rem; }
        .dark .card { background:#1e293b; border-color:#334155; }
        .btn-primary { background:linear-gradient(to right,#3b82f6,#06b6d4); color:#fff; padding:0.625rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; transition:all 0.2s; box-shadow:0 4px 6px rgba(59,130,246,0.25); }
        .btn-primary:hover { background:linear-gradient(to right,#2563eb,#0891b2); }
        .btn-secondary { background:#f1f5f9; color:#334155; padding:0.625rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; transition:all 0.2s; }
        .btn-secondary:hover { background:#e2e8f0; }
        .dark .btn-secondary { background:#334155; color:#cbd5e1; }
        .dark .btn-secondary:hover { background:#475569; }
        .btn-danger { background:linear-gradient(to right,#ef4444,#f43f5e); color:#fff; padding:0.625rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; }
        .form-input { width:100%; border:1px solid #e2e8f0; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; background:#f8fafc; transition:all 0.2s; }
        .form-input:focus { ring:2px solid #60a5fa; border-color:transparent; }
        .dark .form-input { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        .form-label { display:block; font-size:0.875rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .dark .form-label { color:#94a3b8; }
        .badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; }
        .table-head { background:linear-gradient(to right,#f8fafc,#eff6ff); color:#475569; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; }
        .dark .table-head { background:linear-gradient(to right,#0f172a,#1e293b); color:#94a3b8; }
        .scrollbar-hide::-webkit-scrollbar { display:none; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeIn 0.4s ease-out; }

        .dark body { background:#0f172a; color:#e2e8f0; }
        .dark .bg-white { background:#1e293b !important; }
        .dark .bg-slate-50 { background:#0f172a !important; }
        .dark .bg-slate-100 { background:#1e293b !important; }
        .dark .text-slate-800 { color:#e2e8f0 !important; }
        .dark .text-slate-700 { color:#cbd5e1 !important; }
        .dark .text-slate-600 { color:#94a3b8 !important; }
        .dark .text-slate-500 { color:#64748b !important; }
        .dark .text-slate-400 { color:#475569 !important; }
        .dark .border-slate-100 { border-color:#334155 !important; }
        .dark .border-slate-200 { border-color:#334155 !important; }
        .dark .divide-slate-50 > * { border-color:#1e293b !important; }
        .dark .hover\:bg-slate-50:hover { background:#1e293b !important; }
        .dark header { background:#1e293b !important; border-color:#334155 !important; }
        .dark .bg-blue-50 { background:#1e3a5f !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    <aside id="sidebar" class="w-64 flex-shrink-0 bg-gradient-to-b from-slate-800 via-slate-800 to-slate-900 flex flex-col transition-all duration-300 z-30">

        <div class="px-6 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shirt text-white text-base"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-base leading-tight">LaundryPOS</h1>
                    <p class="text-slate-400 text-xs">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto scrollbar-hide">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-4 mb-2">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                <i class="fas fa-receipt w-5 text-center"></i> Transaksi
            </a>

            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-4 mb-2 mt-6">Master Data</p>

            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-center"></i> Pelanggan
            </a>

            <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5 text-center"></i> Layanan
            </a>

            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-4 mb-2 mt-6">Laporan</p>

            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5 text-center"></i> Laporan
            </a>

            <a href="{{ route('admin.profile') }}" class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-5 text-center"></i> Profil
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="bg-white border-b border-slate-100 px-6 py-6 flex items-center justify-between flex-shrink-0 shadow-sm transition-colors duration-300">
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('sidebar').classList.toggle('hidden')" class="text-slate-400 hover:text-slate-600 lg:hidden">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="text-slate-800 font-bold text-lg leading-tight">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-slate-400 text-xs">@yield('page-subtitle', 'LaundryPOS Admin Panel')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-calendar-alt text-slate-400 text-sm"></i>
                    <span class="text-slate-600 text-sm font-medium" id="current-date"></span>
                </div>

                <button id="theme-toggle" onclick="toggleTheme()" class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200" title="Toggle Dark Mode">
                    <i id="theme-icon" class="fas fa-moon text-sm"></i>
                </button>

                <div class="flex items-center gap-2 bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 shadow-inner">
                    <i class="fas fa-clock text-cyan-400 text-sm"></i>
                    <span class="text-white font-bold text-sm font-mono tracking-wide" id="live-clock">00:00:00</span>
                </div>
            </div>
        </header>

        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 mb-4 fade-in alert-dismiss">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 mb-4 fade-in alert-dismiss">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 fade-in alert-dismiss">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="text-sm font-semibold">Terdapat Kesalahan :</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    const d = new Date();
    const options = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
    document.getElementById('current-date').textContent = d.toLocaleDateString('id-ID', options);

    function updateClock() {
        const now  = new Date();
        const h    = String(now.getHours()).padStart(2, '0');
        const m    = String(now.getMinutes()).padStart(2, '0');
        const s    = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('live-clock').textContent = h + ':' + m + ':' + s;
    }
    updateClock();
    setInterval(updateClock, 1000);

    setTimeout(() => {
        document.querySelectorAll('.alert-dismiss').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);

    const html = document.getElementById('html-root');
    const icon = document.getElementById('theme-icon');

    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
        icon.classList.replace('fa-moon', 'fa-sun');
    }

    function toggleTheme() {
        const isDark = html.classList.toggle('dark');
        if (isDark) {
            icon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            icon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        }
    }
</script>

@stack('scripts')
</body>
</html>