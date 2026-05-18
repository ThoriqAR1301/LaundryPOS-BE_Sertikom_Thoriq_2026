<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
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

        #loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }
        #loading-overlay.active {
            display: flex;
            animation: fadeInOverlay 0.2s ease-out;
        }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .spinner-ring {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.15);
            border-top-color: #38bdf8;
            border-right-color: #818cf8;
            animation: spin 0.75s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spinner-text {
            color: #e2e8f0;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            animation: pulse-text 1s ease-in-out infinite;
        }
        @keyframes pulse-text {
            0%, 100% { opacity: 0.6; }
            50%       { opacity: 1; }
        }

        #toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 0.875rem 1.125rem;
            border-radius: 1rem;
            min-width: 300px;
            max-width: 380px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            animation: slideInRight 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }
        .toast.toast-hide {
            animation: slideOutRight 0.3s ease-in forwards;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(120%) scale(0.92); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to   { opacity: 0; transform: translateX(120%) scale(0.9); }
        }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 1rem 1rem;
            animation: shrink linear forwards;
        }
        @keyframes shrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
        .toast-success {
            background: linear-gradient(135deg, rgba(6,78,59,0.95), rgba(5,150,105,0.92));
            border-color: rgba(16,185,129,0.4);
        }
        .toast-success .toast-progress { background: #34d399; }
        .toast-error {
            background: linear-gradient(135deg, rgba(127,29,29,0.95), rgba(185,28,28,0.92));
            border-color: rgba(239,68,68,0.4);
        }
        .toast-error .toast-progress { background: #f87171; }
        .toast-warning {
            background: linear-gradient(135deg, rgba(120,53,15,0.95), rgba(180,83,9,0.92));
            border-color: rgba(245,158,11,0.4);
        }
        .toast-warning .toast-progress { background: #fbbf24; }
        .toast-info {
            background: linear-gradient(135deg, rgba(30,58,138,0.95), rgba(29,78,216,0.92));
            border-color: rgba(59,130,246,0.4);
        }
        .toast-info .toast-progress { background: #60a5fa; }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }
        .toast-success .toast-icon { background: rgba(16,185,129,0.25); color: #6ee7b7; }
        .toast-error   .toast-icon { background: rgba(239,68,68,0.25);  color: #fca5a5; }
        .toast-warning .toast-icon { background: rgba(245,158,11,0.25); color: #fde68a; }
        .toast-info    .toast-icon { background: rgba(59,130,246,0.25); color: #93c5fd; }

        .toast-body { flex: 1; }
        .toast-title { font-weight: 700; font-size: 0.8rem; color: #f1f5f9; margin-bottom: 0.1rem; }
        .toast-message { font-size: 0.78rem; color: #cbd5e1; line-height: 1.45; }
        .toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.1rem;
            flex-shrink: 0;
            transition: color 0.15s;
            font-size: 0.75rem;
            margin-top: 0.1rem;
        }
        .toast-close:hover { color: #f1f5f9; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 transition-colors duration-300">

<div id="loading-overlay" role="status" aria-live="polite">
    <div class="spinner-ring"></div>
    <p class="spinner-text">Memproses...</p>
</div>

<div id="toast-container" aria-live="polite" aria-atomic="false"></div>

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
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 text-slate-400 hover:bg-red-500 hover:text-white transition-all duration-200" title="Logout">
                        <i class="fas fa-sign-out-alt text-sm"></i>
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

                <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-clock text-cyan-500 text-sm"></i>
                    <span class="text-slate-600 text-sm font-bold font-mono tracking-wide" id="live-clock">00:00:00</span>
                </div>
            </div>
        </header>

        <div class="px-6 pt-4 space-y-3">
            
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
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
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

const loadingOverlay = document.getElementById('loading-overlay');

function showLoading(text) {
    const label = loadingOverlay.querySelector('.spinner-text');
    if (label) label.textContent = text || 'Memproses...';
    loadingOverlay.classList.add('active');
}

function hideLoading() {
    loadingOverlay.classList.remove('active');
}

window.addEventListener('pageshow', () => hideLoading());

document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-no-loading')) return;

    const btn = form.querySelector('[type="submit"]');
    const btnText = btn ? btn.innerText.trim() : '';
    const loadingTexts = {
        'Simpan'        : 'Menyimpan Data...',
        'Perbarui'      : 'Memperbarui Data...',
        'Buat Transaksi': 'Membuat Transaksi...',
        'Tampilkan'     : 'Memuat Laporan...',
        'Filter'        : 'Memfilter Data...',
        'Upload'        : 'Mengupload File...',
        'Update Status' : 'Memperbarui Status...',
        'Masuk Sekarang': 'Masuk...',
        'Buat Akun Sekarang': 'Membuat Akun...',
    };
    const text = Object.keys(loadingTexts).find(k => btnText.includes(k));
    showLoading(text ? loadingTexts[text] : 'Memproses...');
});

document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href !== '#' && !this.classList.contains('active')) {
            showLoading('Memuat Halaman...');
        }
    });
});

const LaundryToast = (() => {
    const container = document.getElementById('toast-container');
    const DURATION  = 4500; 

    const iconMap = {
        success : 'fas fa-check',
        error   : 'fas fa-times',
        warning : 'fas fa-exclamation',
        info    : 'fas fa-info',
    };
    const titleMap = {
        success : 'Berhasil!',
        error   : 'Gagal!',
        warning : 'Perhatian!',
        info    : 'Informasi',
    };

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
            <div class="toast-progress" style="animation-duration: ${dur}ms"></div>
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
        success : (title, msg, dur) => show('success', title, msg, dur),
        error : (title, msg, dur) => show('error', title, msg, dur),
        warning : (title, msg, dur) => show('warning', title, msg, dur),
        info : (title, msg, dur) => show('info', title, msg, dur),
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
    LaundryToast.warning('Terdapat Kesalahan!', @json($errors->first()));
@endif
@if(session('info'))
    LaundryToast.info('Informasi', @json(session('info')));
@endif
</script>

@stack('scripts')
</body>
</html>