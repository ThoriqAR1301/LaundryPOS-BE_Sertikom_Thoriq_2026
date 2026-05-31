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
        .btn-primary { background:linear-gradient(to right,#3b82f6,#06b6d4); color:#fff; padding:0.825rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; transition:all 0.2s; box-shadow:0 4px 6px rgba(59,130,246,0.25); }
        .btn-primary:hover { background:linear-gradient(to right,#2563eb,#0891b2); }
        .btn-secondary { background:#f1f5f9; color:#334155; padding:0.825rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; transition:all 0.2s; }
        .btn-secondary:hover { background:#e2e8f0; }
        .dark .btn-secondary { background:#334155; color:#cbd5e1; }
        .dark .btn-secondary:hover { background:#475569; }
        .btn-danger { background:linear-gradient(to right,#ef4444,#f43f5e); color:#fff; padding:0.625rem 1.25rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.5rem; }
        .form-input { width:100%; border:1px solid #e2e8f0; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; background:#f8fafc; transition:all 0.2s; }
        .form-input:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,0.15); }
        .form-input.input-error { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.12); }
        .form-input.input-ok { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .dark .form-input { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        .form-label { display:block; font-size:0.875rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .dark .form-label { color:#94a3b8; }
        .field-error { font-size:0.75rem; color:#ef4444; margin-top:0.25rem; display:flex; align-items:center; gap:0.25rem; }
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
            position:fixed; inset:0;
            background:rgba(15,23,42,0.55);
            backdrop-filter:blur(4px);
            z-index:9999; display:none;
            align-items:center; justify-content:center; flex-direction:column; gap:1rem;
        }
        #loading-overlay.active { display:flex; animation:fadeInOverlay 0.2s ease-out; }
        @keyframes fadeInOverlay { from{opacity:0} to{opacity:1} }
        .spinner-ring {
            width:56px; height:56px; border-radius:50%;
            border:4px solid rgba(255,255,255,0.15);
            border-top-color:#38bdf8; border-right-color:#818cf8;
            animation:spin 0.75s linear infinite;
        }
        @keyframes spin { to{transform:rotate(360deg)} }
        .spinner-text {
            color:#e2e8f0; font-size:0.8rem; font-weight:600;
            letter-spacing:0.08em; text-transform:uppercase;
            animation:pulseTxt 1s ease-in-out infinite;
        }
        @keyframes pulseTxt { 0%,100%{opacity:0.6} 50%{opacity:1} }

        #toast-container {
            position:fixed; top:1.25rem; right:1.25rem;
            z-index:10000; display:flex; flex-direction:column; gap:0.625rem;
            pointer-events:none;
        }
        .toast {
            pointer-events:all;
            display:flex; align-items:flex-start; gap:0.875rem;
            padding:0.875rem 1.125rem; border-radius:1rem;
            min-width:300px; max-width:380px;
            box-shadow:0 8px 32px rgba(0,0,0,0.18);
            border:1px solid rgba(255,255,255,0.12);
            backdrop-filter:blur(12px);
            animation:slideInRight 0.35s cubic-bezier(0.34,1.56,0.64,1);
            position:relative; overflow:hidden;
        }
        .toast.toast-hide { animation:slideOutRight 0.3s ease-in forwards; }
        @keyframes slideInRight { from{opacity:0;transform:translateX(120%) scale(0.92)} to{opacity:1;transform:translateX(0) scale(1)} }
        @keyframes slideOutRight { from{opacity:1;transform:translateX(0) scale(1)} to{opacity:0;transform:translateX(120%) scale(0.9)} }
        .toast-progress { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 1rem 1rem; animation:shrink linear forwards; }
        @keyframes shrink { from{width:100%} to{width:0%} }
        .toast-success { background:linear-gradient(135deg,rgba(6,78,59,0.95),rgba(5,150,105,0.92)); border-color:rgba(16,185,129,0.4); }
        .toast-success .toast-progress { background:#34d399; }
        .toast-error { background:linear-gradient(135deg,rgba(127,29,29,0.95),rgba(185,28,28,0.92)); border-color:rgba(239,68,68,0.4); }
        .toast-error .toast-progress { background:#f87171; }
        .toast-warning { background:linear-gradient(135deg,rgba(120,53,15,0.95),rgba(180,83,9,0.92)); border-color:rgba(245,158,11,0.4); }
        .toast-warning .toast-progress { background:#fbbf24; }
        .toast-info { background:linear-gradient(135deg,rgba(30,58,138,0.95),rgba(29,78,216,0.92)); border-color:rgba(59,130,246,0.4); }
        .toast-info .toast-progress { background:#60a5fa; }
        .toast-icon { width:36px; height:36px; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.9rem; }
        .toast-success .toast-icon { background:rgba(16,185,129,0.25); color:#6ee7b7; }
        .toast-error .toast-icon { background:rgba(239,68,68,0.25);  color:#fca5a5; }
        .toast-warning .toast-icon { background:rgba(245,158,11,0.25); color:#fde68a; }
        .toast-info .toast-icon { background:rgba(59,130,246,0.25); color:#93c5fd; }
        .toast-body { flex:1; }
        .toast-title { font-weight:700; font-size:0.8rem; color:#f1f5f9; margin-bottom:0.1rem; }
        .toast-message { font-size:0.78rem; color:#cbd5e1; line-height:1.45; }
        .toast-close { background:none; border:none; color:#94a3b8; cursor:pointer; padding:0.1rem; flex-shrink:0; transition:color 0.15s; font-size:0.75rem; margin-top:0.1rem; }
        .toast-close:hover { color:#f1f5f9; }

        #confirm-overlay {
            position:fixed; inset:0;
            background:rgba(15,23,42,0.6);
            backdrop-filter:blur(4px);
            z-index:9998; display:none;
            align-items:center; justify-content:center; padding:1rem;
        }
        #confirm-overlay.active { display:flex; animation:fadeInOverlay 0.2s ease-out; }
        #confirm-box {
            background:#fff; border-radius:1.25rem;
            padding:1.75rem; max-width:400px; width:100%;
            box-shadow:0 25px 50px rgba(0,0,0,0.25);
            animation: confirmSlideUp 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }
        .dark #confirm-box { background:#1e293b; }

        @keyframes confirmSlideUp {
            from { opacity:0; transform:translateY(60px) scale(0.95); }
            to   { opacity:1; transform:translateY(0)   scale(1);    }
        }
        @keyframes confirmSlideDown {
            from { opacity:1; transform:translateY(0)   scale(1);    }
            to   { opacity:0; transform:translateY(60px) scale(0.95); }
        }

        #confirm-icon { width:52px; height:52px; border-radius:1rem; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; font-size:1.25rem; }
        #confirm-title { font-weight:700; font-size:1rem; color:#0f172a; margin-bottom:0.375rem; }
        #confirm-message { font-size:0.875rem; color:#64748b; line-height:1.6; margin-bottom:1.5rem; }
        .dark #confirm-title { color:#e2e8f0; }
        .dark #confirm-message { color:#94a3b8; }
        #confirm-actions { display:flex; gap:0.75rem; }
        #confirm-actions button { flex:1; padding:0.7rem; border-radius:0.75rem; font-weight:600; font-size:0.875rem; cursor:pointer; transition:all 0.2s; border:none; }
        #confirm-cancel { background:#f1f5f9; color:#334155; }
        #confirm-cancel:hover { background:#e2e8f0; }
        .dark #confirm-cancel { background:#334155; color:#cbd5e1; }
        #confirm-ok { color:#fff; }

        @keyframes slideInNotif {
            from { opacity:0; transform:translateY(-8px) scale(0.97); }
            to { opacity:1; transform:translateY(0) scale(1); }
        }
        @keyframes slideOutNotif {
            from { opacity:1; transform:translateY(0) scale(1); }
            to { opacity:0; transform:translateY(-8px) scale(0.97); }
        }
        .notif-item { display:flex; align-items:flex-start; gap:0.75rem; padding:0.875rem 1rem; transition:background 0.15s; cursor:default; }
        .notif-item:hover { background:#f8fafc; }
        .dark .notif-item:hover { background:#1e293b; }
        .notif-item-icon { width:34px; height:34px; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.8rem; }
        .notif-item-body { flex:1; min-width:0; }
        .notif-item-title { font-size:0.8rem; font-weight:700; color:#1e293b; margin-bottom:0.15rem; }
        .dark .notif-item-title { color:#e2e8f0; }
        .notif-item-msg { font-size:0.75rem; color:#64748b; line-height:1.4; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .notif-item-time { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }
        .notif-success .notif-item-icon { background:#d1fae5; color:#10b981; }
        .notif-error .notif-item-icon { background:#fee2e2; color:#ef4444; }
        .notif-warning .notif-item-icon { background:#fef3c7; color:#f59e0b; }
        .notif-info .notif-item-icon { background:#dbeafe; color:#3b82f6; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 transition-colors duration-300">

<div id="loading-overlay" role="status" aria-live="polite">
    <div class="spinner-ring"></div>
    <p class="spinner-text" id="loading-text">Memproses...</p>
</div>

<div id="toast-container" aria-live="polite" aria-atomic="false"></div>

<div id="confirm-overlay" role="dialog" aria-modal="true">
    <div id="confirm-box">
        <div id="confirm-icon"></div>
        <p id="confirm-title"></p>
        <p id="confirm-message"></p>
        <div id="confirm-actions">
            <button id="confirm-cancel" onclick="LaundryConfirm.cancel()"></button>
            <button id="confirm-ok" onclick="LaundryConfirm.confirm()"></button>
        </div>
    </div>
</div>

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
                <form action="{{ route('logout') }}" method="POST" data-confirm-title="Keluar Dari Sistem?" data-confirm-message="Anda Akan Logout Dari LaundryPOS. Pastikan Semua Pekerjaan Sudah Tersimpan" data-confirm-type="warning" data-confirm-ok="Ya, Logout" data-confirm-cancel="Batal" data-no-loading>
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

                <div class="relative" id="notif-wrapper">
                    <button onclick="toggleNotif()" class="relative w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200" title="Notifikasi">
                        <i class="fas fa-bell text-sm"></i>
                        <span id="notif-badge" class="hidden absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold leading-none px-1"></span>
                    </button>

                    <div id="notif-dropdown" class="hidden absolute right-0 top-12 w-80 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden dark:bg-slate-800 dark:border-slate-700" style="animation:slideInNotif 0.2s ease-out">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                                    <i class="fas fa-bell text-xs text-blue-500 dark:text-blue-300"></i>
                                </div>
                                <span class="font-bold text-slate-800 text-sm dark:text-slate-200">Notifikasi</span>
                                <span id="notif-count-label" class="hidden text-xs bg-blue-100 text-blue-600 font-bold px-2 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"></span>
                            </div>
                            <button onclick="clearAllNotif()" class="text-xs text-slate-400 hover:text-red-500 transition-colors font-medium">Hapus Semua</button>
                        </div>

                        <div id="notif-list" class="max-h-80 overflow-y-auto scrollbar-hide divide-y divide-slate-50 dark:divide-slate-700">
                            <div id="notif-empty" class="flex flex-col items-center justify-center py-10 gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center dark:bg-slate-700">
                                    <i class="fas fa-bell-slash text-slate-400 text-lg"></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium">Belum Ada Notifikasi</p>
                            </div>
                        </div>

                        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-700 text-center">
                            <p class="text-xs text-slate-400">Notifikasi Tersimpan Selama Sesi Ini</p>
                        </div>
                    </div>
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
document.getElementById('current-date').textContent = d.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
function updateClock() {
    const n = new Date();
    document.getElementById('live-clock').textContent =
        String(n.getHours()).padStart(2,'0') + ':' +
        String(n.getMinutes()).padStart(2,'0') + ':' +
        String(n.getSeconds()).padStart(2,'0');
}
updateClock(); setInterval(updateClock, 1000);

setTimeout(() => {
    document.querySelectorAll('.alert-dismiss').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);

const html = document.getElementById('html-root');
const icon = document.getElementById('theme-icon');
if (localStorage.getItem('theme') === 'dark') { html.classList.add('dark'); icon.classList.replace('fa-moon','fa-sun'); }
function toggleTheme() {
    const isDark = html.classList.toggle('dark');
    icon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

const loadingOverlay = document.getElementById('loading-overlay');
const loadingText = document.getElementById('loading-text');

function showLoading(text) {
    loadingText.textContent = text || 'Memproses...';
    loadingOverlay.classList.add('active');
}
function hideLoading() { loadingOverlay.classList.remove('active'); }
window.addEventListener('pageshow', () => hideLoading());

document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-no-loading')) return;

    const btn = form.querySelector('[type="submit"]');
    const text = btn ? btn.innerText.trim() : '';
    const map = {
        'Simpan':'Menyimpan Data...', 'Perbarui':'Memperbarui Data...',
        'Buat Transaksi':'Membuat Transaksi...', 'Tampilkan':'Memuat Laporan...',
        'Filter':'Memfilter Data...', 'Upload':'Mengupload File...',
        'Update Status':'Memperbarui Status...', 'Masuk Sekarang':'Masuk...',
        'Buat Akun Sekarang':'Membuat Akun...',
    };
    const key = Object.keys(map).find(k => text.includes(k));
    showLoading(key ? map[key] : 'Memproses...');
});

document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function() {
        const href = this.getAttribute('href');
        if (href && href !== '#' && !this.classList.contains('active')) {
            showLoading('Memuat Halaman...');
        }
    });
});

const LaundryToast = (() => {
    const container = document.getElementById('toast-container');
    const DURATION = 4500;
    const iconMap  = { success:'fas fa-check', error:'fas fa-times', warning:'fas fa-exclamation', info:'fas fa-info' };
    const titleMap = { success:'Berhasil!', error:'Gagal!', warning:'Perhatian!', info:'Informasi' };

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
            <button class="toast-close" onclick="LaundryToast.dismiss(this.closest('.toast'))"><i class="fas fa-times"></i></button>
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
        info    : (t, m, d) => show('info',    t, m, d),
        dismiss,
    };
})();

const NotifStore = (() => {
    const KEY = 'laundry_notifs';
    const iconMap = {
        success: 'fas fa-check',
        error: 'fas fa-times',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info',
    };

    function getAll() {
        try { return JSON.parse(sessionStorage.getItem(KEY) || '[]'); } catch { return []; }
    }

    function add(type, title, message) {
        const list = getAll();
        list.unshift({
            id: Date.now(),
            type, title,
            message: message || '',
            time: new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' }),
        });
        sessionStorage.setItem(KEY, JSON.stringify(list.slice(0, 20)));
        renderNotif();
    }

    function clear() {
        sessionStorage.removeItem(KEY);
        renderNotif();
    }

    function renderNotif() {
        const list = getAll();
        const listEl = document.getElementById('notif-list');
        const emptyEl = document.getElementById('notif-empty');
        const badge = document.getElementById('notif-badge');
        const countLbl = document.getElementById('notif-count-label');

        listEl.querySelectorAll('.notif-item').forEach(el => el.remove());

        if (list.length === 0) {
            emptyEl.style.display = 'flex';
            badge.classList.add('hidden');
            countLbl.classList.add('hidden');
            return;
        }

        emptyEl.style.display = 'none';
        badge.classList.remove('hidden');
        badge.textContent = list.length > 9 ? '9+' : list.length;
        countLbl.classList.remove('hidden');
        countLbl.textContent = list.length + ' item';

        list.forEach(n => {
            const div = document.createElement('div');
            div.className = `notif-item notif-${n.type}`;
            div.innerHTML = `
                <div class="notif-item-icon"><i class="${iconMap[n.type] || 'fas fa-bell'}"></i></div>
                <div class="notif-item-body">
                    <p class="notif-item-title">${n.title}</p>
                    ${n.message ? `<p class="notif-item-msg">${n.message}</p>` : ''}
                    <p class="notif-item-time"><i class="fas fa-clock text-xs mr-1"></i>${n.time}</p>
                </div>
            `;
            listEl.appendChild(div);
        });
    }

    return { add, clear, renderNotif };
})();

function toggleNotif() {
    const dd = document.getElementById('notif-dropdown');
    const isHidden = dd.classList.contains('hidden');
    if (isHidden) {
        dd.classList.remove('hidden');
        dd.style.animation = 'none';
        void dd.offsetWidth;
        dd.style.animation = 'slideInNotif 0.2s ease-out forwards';
    } else {
        dd.style.animation = 'slideOutNotif 0.18s ease-in forwards';
        setTimeout(() => {
            dd.classList.add('hidden');
            dd.style.animation = '';
        }, 175);
    }
}

function clearAllNotif() {
    NotifStore.clear();
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    const dd      = document.getElementById('notif-dropdown');
    if (wrapper && !wrapper.contains(e.target) && !dd.classList.contains('hidden')) {
        dd.style.animation = 'slideOutNotif 0.18s ease-in forwards';
        setTimeout(() => {
            dd.classList.add('hidden');
            dd.style.animation = '';
        }, 175);
    }
});

['success','error','warning','info'].forEach(type => {
    const orig = LaundryToast[type].bind(LaundryToast);
    LaundryToast[type] = (title, message, duration) => {
        NotifStore.add(type, title, message);
        orig(title, message, duration);
    };
});

@if(session('success')) LaundryToast.success('Berhasil!', @json(session('success'))); @endif
@if(session('error')) LaundryToast.error('Gagal!', @json(session('error'))); @endif
@if(session('info')) LaundryToast.info('Informasi', @json(session('info'))); @endif
@if($errors->any()) LaundryToast.warning('Terdapat Kesalahan!', @json($errors->first())); @endif

const LaundryConfirm = (() => {
    let _resolve = null;

    const overlay = document.getElementById('confirm-overlay');
    const box = document.getElementById('confirm-box');
    const iconEl = document.getElementById('confirm-icon');
    const titleEl = document.getElementById('confirm-title');
    const msgEl = document.getElementById('confirm-message');
    const cancelEl = document.getElementById('confirm-cancel');
    const okEl = document.getElementById('confirm-ok');

    const typeConfig = {
        danger: { icon:'fas fa-trash', bg:'#fee2e2', iconColor:'#ef4444', okBg:'linear-gradient(to right,#ef4444,#f43f5e)', okText:'Hapus' },
        warning: { icon:'fas fa-exclamation', bg:'#fef3c7', iconColor:'#f59e0b', okBg:'linear-gradient(to right,#f59e0b,#f97316)', okText:'Lanjutkan' },
        info: { icon:'fas fa-question-circle', bg:'#dbeafe', iconColor:'#3b82f6', okBg:'linear-gradient(to right,#3b82f6,#06b6d4)', okText:'OK' },
    };

    function playSlideUp() {
        box.style.animation = 'none';
        void box.offsetWidth;
        box.style.animation = 'confirmSlideUp 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards';
    }

    function playSlideDown(callback) {
        box.style.animation = 'confirmSlideDown 0.25s ease-in forwards';
        setTimeout(() => {
            overlay.classList.remove('active');
            box.style.animation = '';
            if (callback) callback();
        }, 240);
    }

    function show({ title, message, type = 'danger', confirmText, cancelText, onConfirm }) {
        const cfg = typeConfig[type] || typeConfig.danger;
        iconEl.innerHTML = `<i class="${cfg.icon}" style="color:${cfg.iconColor}"></i>`;
        iconEl.style.background = cfg.bg;
        titleEl.textContent = title || 'Yakin?';
        msgEl.textContent = message || 'Aksi Ini Tidak Bisa Dibatalkan';
        cancelEl.textContent = cancelText || 'Batal';
        okEl.textContent = confirmText || cfg.okText;
        okEl.style.background = cfg.okBg;
        overlay.classList.add('active');
        playSlideUp();
        _resolve = onConfirm || null;
        return false;
    }

    function confirm() {
        overlay.classList.remove('active');
        box.style.animation = '';
        if (_resolve) { _resolve(); _resolve = null; }
    }

    function cancel() {
        playSlideDown(() => { _resolve = null; });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-confirm-title]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                show({
                    title: this.dataset.confirmTitle,
                    message: this.dataset.confirmMessage || 'Aksi Ini Tidak Bisa Dibatalkan',
                    type: this.dataset.confirmType || 'danger',
                    confirmText: this.dataset.confirmOk,
                    cancelText: this.dataset.confirmCancel,
                    onConfirm: () => { this.removeAttribute('data-confirm-title'); this.submit(); }
                });
            });
        });
    });

    return { show, confirm, cancel };
})();

const LaundryValidate = (() => {
    const rules = {
        required: (v) => v.trim() !== '' || 'Wajib Diisi',
        minLength: (v, n) => v.length >= parseInt(n) || `Minimal ${n} Karakter`,
        maxLength: (v, n) => v.length <= parseInt(n) || `Maksimal ${n} Karakter`,
        email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Format Email Tidak Valid',
        phone: (v) => /^[\d\s\-\+]{8,15}$/.test(v) || 'Format Nomor HP Tidak Valid',
        number: (v) => !isNaN(v) && v !== '' || 'Harus Berupa Angka',
        match: (v, sel, f) => v === (document.querySelector(sel)?.value || '') || `Harus Sama Dengan ${f || 'Field Di Atas'}`,
    };

    function validateField(input) {
        const d = input.dataset;
        const v = input.value;
        let error = null;
        if (d.required) { const r = rules.required(v); if (r !== true) error = r; }
        if (!error && d.minLength) { const r = rules.minLength(v, d.minLength); if (r !== true) error = r; }
        if (!error && d.maxLength) { const r = rules.maxLength(v, d.maxLength); if (r !== true) error = r; }
        if (!error && d.type==='email'){ const r = rules.email(v); if (r !== true) error = r; }
        if (!error && d.type==='phone'){ const r = rules.phone(v); if (r !== true) error = r; }
        if (!error && d.type==='number'){const r = rules.number(v); if (r !== true) error = r; }
        if (!error && d.match) { const r = rules.match(v, d.match, d.matchLabel); if (r !== true) error = r; }

        showFieldState(input, error);
        return !error;
    }

    function showFieldState(input, error) {
        input.classList.toggle('input-error', !!error);
        input.classList.toggle('input-ok', !error && input.value !== '');

        let errEl = input.parentElement.querySelector('.field-error');
        if (error) {
            if (!errEl) { errEl = document.createElement('p'); errEl.className = 'field-error'; input.parentElement.appendChild(errEl); }
            errEl.innerHTML = `<i class="fas fa-circle-exclamation"></i> ${error}`;
        } else { if (errEl) errEl.remove(); }
    }

    function validateForm(form) {
        const inputs = form.querySelectorAll('[data-required],[data-min-length],[data-type],[data-match]');
        let valid = true;
        inputs.forEach(inp => { if (!validateField(inp)) valid = false; });
        return valid;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-required],[data-min-length],[data-type],[data-match]').forEach(input => {
            input.addEventListener('blur',  () => validateField(input));
            input.addEventListener('input', () => { if (input.classList.contains('input-error')) validateField(input); });
        });

        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault(); e.stopPropagation();
                    LaundryToast.warning('Formulir Tidak Lengkap', 'Periksa Kembali Isian Yang Ditandai Merah');
                }
            }, true);
        });
    });

    return { validateField, validateForm };
})();

document.addEventListener('DOMContentLoaded', () => {
    NotifStore.renderNotif();

    document.querySelectorAll('input.debounce-search').forEach(input => {
        const delay = parseInt(input.dataset.debounce || '500');
        let timer = null;
        input.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => { const form = this.closest('form'); if (form) form.submit(); }, delay);
        });
    });
});
</script>

@stack('scripts')
</body>
</html>