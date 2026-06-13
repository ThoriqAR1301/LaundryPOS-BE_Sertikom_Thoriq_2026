<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Selamat Datang — LaundryPOS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        body {
            margin: 0; padding: 0; overflow: hidden;
            background: #060d1a;
            min-height: 100vh;
        }

        @keyframes fadeIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeOut { from { opacity:1; } to { opacity:0; } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-14px); } }
        @keyframes pulse { 0%,100% { opacity:0.6; transform:scale(1); } 50% { opacity:1; transform:scale(1.04); } }
        @keyframes slideIn { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(-40px); } }
        @keyframes shimmer { 0% { background-position:-200% center; } 100% { background-position:200% center; } }
        @keyframes dotPulse { 0%,100% { transform:scale(1); } 50% { transform:scale(1.3); } }

        .float-icon { animation: float 3s ease-in-out infinite; }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .slide-in { animation: slideIn 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        .shimmer-text {
            background: linear-gradient(90deg, #f1f5f9 0%, #38bdf8 40%, #a78bfa 60%, #f1f5f9 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        .slide-bg {
            position: absolute; inset: 0;
            transition: opacity 0.6s ease;
        }

        .progress-bar {
            transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
        }

        .dot-active { animation: dotPulse 1.5s ease-in-out infinite; }

        .bubble {
            position: absolute; border-radius: 9999px;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>

<div id="onboarding" class="relative w-full h-screen flex flex-col overflow-hidden">

    <div id="bg-slide" class="slide-bg" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 50%, #0f2952 100%)"></div>

    <div class="bubble" style="width:280px;height:280px;top:-100px;right:-80px;opacity:0.05"></div>
    <div class="bubble" style="width:180px;height:180px;bottom:-60px;left:-60px;opacity:0.04"></div>
    <div class="bubble" style="width:100px;height:100px;top:30%;left:5%;opacity:0.03"></div>
    <div class="bubble" style="width:60px;height:60px;top:20%;right:15%;opacity:0.04"></div>

    <div class="relative z-10 flex items-center justify-between px-8 pt-8 pb-4 fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#1d4ed8,#38bdf8)">
                <i class="fas fa-shirt text-white text-base"></i>
            </div>
            <span class="text-white font-bold text-lg tracking-tight">LaundryPOS</span>
        </div>
        <button onclick="skipOnboarding()" class="text-sm font-semibold px-4 py-2 rounded-xl transition-all hover:bg-white/10" style="color:rgba(255,255,255,0.45)">
            Lewati <i class="fas fa-forward-step ml-1 text-xs"></i>
        </button>
    </div>

    <div class="relative z-10 px-8 mb-2">
        <div class="w-full h-1 rounded-full" style="background:rgba(255,255,255,0.08)">
            <div id="progress-bar" class="h-full rounded-full progress-bar" style="width:33.33%;background:linear-gradient(to right,#3b82f6,#38bdf8)"></div>
        </div>
    </div>

    <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-8 py-4" id="slide-content">

        <div class="float-icon mb-10" id="slide-icon-wrap">
            <div class="relative">
                <div id="slide-ring-outer" class="flex items-center justify-center rounded-full" style="width:200px;height:200px;border:1.5px solid rgba(56,189,248,0.2)">
                    <div id="slide-ring-inner" class="flex items-center justify-center rounded-full" style="width:162px;height:162px;border:1px solid rgba(56,189,248,0.35)">
                        <div id="slide-icon-box" class="flex items-center justify-center rounded-3xl shadow-2xl" style="width:120px;height:120px;background:linear-gradient(135deg,#1d4ed8,#3b82f6,#38bdf8)">
                            <i id="slide-icon" class="fas fa-shirt text-white" style="font-size:48px"></i>
                        </div>
                    </div>
                </div>
                <div id="slide-glow" class="absolute -bottom-4 left-1/2 -translate-x-1/2 rounded-full" style="width:120px;height:28px;background:rgba(56,189,248,0.15);filter:blur(8px)"></div>
            </div>
        </div>

        <div class="text-center max-w-lg" id="slide-text">
            <h1 id="slide-title" class="text-4xl font-extrabold mb-5 leading-tight tracking-tight shimmer-text">
                Selamat Datang Di<br>LaundryPOS
            </h1>
            <p id="slide-subtitle" class="text-base leading-relaxed" style="color:rgba(255,255,255,0.5)">
                Solusi Modern Untuk Mengelola Dan Memantau Laundry Anda Secara Efisien, Kapan Saja Dan Di Mana Saja
            </p>
        </div>

    </div>

    <div class="relative z-10 px-8 pb-10 pt-4 flex flex-col items-center gap-6">

        <div class="flex items-center gap-2" id="dot-wrap">
            <div class="dot dot-active rounded-full transition-all duration-300" style="width:24px;height:8px;background:#38bdf8" data-index="0"></div>
            <div class="dot rounded-full transition-all duration-300" style="width:8px;height:8px;background:rgba(255,255,255,0.2)" data-index="1"></div>
            <div class="dot rounded-full transition-all duration-300" style="width:8px;height:8px;background:rgba(255,255,255,0.2)" data-index="2"></div>
        </div>

        <div class="flex items-center gap-3 w-full max-w-sm" id="btn-row">
            <button id="btn-prev" onclick="prevSlide()" class="flex-1 py-3.5 rounded-2xl font-semibold text-sm transition-all" style="display:none;background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.5);border:1px solid rgba(255,255,255,0.1)">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </button>
            <button id="btn-next" onclick="nextSlide()" class="flex-1 py-3.5 rounded-2xl font-bold text-sm text-white transition-all hover:-translate-y-0.5 shadow-lg" style="background:linear-gradient(to right,#1d4ed8,#3b82f6,#38bdf8);box-shadow:0 6px 20px rgba(59,130,246,0.35)">
                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>

    </div>
</div>

<script>
// ── Data Slides ──────────────────────────────────────────────────
const SLIDES = [
    {
        bg       : 'linear-gradient(135deg,#060d1a 0%,#0d1f3c 50%,#0f2952 100%)',
        iconBg   : 'linear-gradient(135deg,#1d4ed8,#3b82f6,#38bdf8)',
        ringOuter: 'rgba(56,189,248,0.2)',
        ringInner: 'rgba(56,189,248,0.35)',
        glow     : 'rgba(56,189,248,0.15)',
        accent   : '#38bdf8',
        icon     : 'fa-shirt',
        title    : 'Selamat Datang di<br>LaundryPOS',
        subtitle : 'Solusi modern untuk mengelola dan memantau laundry Anda secara efisien, kapan saja dan di mana saja.',
        progress : '33.33%',
        btnBg    : 'linear-gradient(to right,#1d4ed8,#3b82f6,#38bdf8)',
        btnShadow: 'rgba(59,130,246,0.35)',
    },
    {
        bg       : 'linear-gradient(135deg,#0f0a1e 0%,#1e0a3c 50%,#2e1065 100%)',
        iconBg   : 'linear-gradient(135deg,#5b21b6,#7c3aed,#a855f7)',
        ringOuter: 'rgba(167,139,250,0.2)',
        ringInner: 'rgba(167,139,250,0.35)',
        glow     : 'rgba(167,139,250,0.15)',
        accent   : '#a78bfa',
        icon     : 'fa-bell',
        title    : 'Pantau Status<br>Cucian Realtime',
        subtitle : 'Dari antrian, dicuci, disetrika, hingga siap diambil — semua terpantau langsung dari dashboard admin.',
        progress : '66.66%',
        btnBg    : 'linear-gradient(to right,#5b21b6,#7c3aed,#a855f7)',
        btnShadow: 'rgba(124,58,237,0.35)',
    },
    {
        bg       : 'linear-gradient(135deg,#071a14 0%,#0a2e22 50%,#022c22 100%)',
        iconBg   : 'linear-gradient(135deg,#065f46,#059669,#10b981)',
        ringOuter: 'rgba(52,211,153,0.2)',
        ringInner: 'rgba(52,211,153,0.35)',
        glow     : 'rgba(52,211,153,0.15)',
        accent   : '#34d399',
        icon     : 'fa-shield-halved',
        title    : 'Aman, Cepat,<br>dan Terpercaya',
        subtitle : 'Kelola transaksi, pelanggan, dan laporan laundry dengan sistem yang aman dan mudah digunakan.',
        progress : '100%',
        btnBg    : 'linear-gradient(to right,#065f46,#059669,#10b981)',
        btnShadow: 'rgba(16,185,129,0.35)',
    },
];

let current = 0;

// ── Render Slide ─────────────────────────────────────────────────
function renderSlide(index, direction = 'next') {
    const s = SLIDES[index];

    // Background
    document.getElementById('bg-slide').style.background = s.bg;

    // Progress bar
    const pb = document.getElementById('progress-bar');
    pb.style.width      = s.progress;
    pb.style.background = s.btnBg;

    // Animasi konten
    const content = document.getElementById('slide-content');
    content.style.animation = '';
    void content.offsetWidth;
    content.style.animation = 'slideIn 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards';

    // Ikon
    document.getElementById('slide-icon').className       = `fas ${s.icon} text-white`;
    document.getElementById('slide-icon-box').style.background = s.iconBg;
    document.getElementById('slide-ring-outer').style.borderColor = s.ringOuter;
    document.getElementById('slide-ring-inner').style.borderColor = s.ringInner;
    document.getElementById('slide-glow').style.background        = s.glow;

    // Teks
    document.getElementById('slide-title').innerHTML    = s.title;
    document.getElementById('slide-subtitle').textContent = s.subtitle;

    // Dots
    document.querySelectorAll('.dot').forEach((dot, i) => {
        if (i === index) {
            dot.style.width      = '24px';
            dot.style.background = s.accent;
            dot.classList.add('dot-active');
        } else {
            dot.style.width      = '8px';
            dot.style.background = 'rgba(255,255,255,0.2)';
            dot.classList.remove('dot-active');
        }
    });

    // Tombol
    function renderSlide(index, direction = 'next') {
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
    

        btnNext.style.background  = s.btnBg;
        btnNext.style.boxShadow   = `0 6px 20px ${s.btnShadow}`;

        if (index === SLIDES.length - 1) {
            btnNext.innerHTML = '<i class="fas fa-rocket mr-2"></i> Mulai Sekarang';
            btnNext.onclick   = finishOnboarding;
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right ml-2"></i>';
            btnNext.onclick   = nextSlide;
        }

        console.log('index:', index, 'btnPrev:', btnPrev);
        if (index > 0) {
            btnPrev.style.display  = 'flex';
            btnPrev.style.setProperty('display', 'flex', 'important');
        } else {
            btnPrev.style.display  = 'none';
            btnPrev.style.setProperty('display', 'none', 'important');
        }
        console.log('btnPrev display after:', btnPrev.style.display);
    }
}

// ── Navigasi ─────────────────────────────────────────────────────
function nextSlide() {
    if (current < SLIDES.length - 1) {
        current++;
        renderSlide(current, 'next');
    }
}

function prevSlide() {
    if (current > 0) {
        current--;
        renderSlide(current, 'prev');
    }
}

function finishOnboarding() {
    document.cookie = 'laundry_onboarding_done=true;path=/;max-age=31536000';
    localStorage.setItem('laundry_onboarding_done', 'true');
    document.getElementById('onboarding').style.animation = 'fadeOut 0.5s ease forwards';
    setTimeout(() => { window.location.href = '{{ route("login") }}'; }, 480);
}

function skipOnboarding() {
    finishOnboarding();
}

// Keyboard support
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight' || e.key === 'Enter') nextSlide();
    if (e.key === 'ArrowLeft')  prevSlide();
    if (e.key === 'Escape')     skipOnboarding();
});

// Swipe support (touch)
let touchStartX = 0;
document.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
document.addEventListener('touchend',   e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) { diff > 0 ? nextSlide() : prevSlide(); }
});

// Init
renderSlide(0);
</script>
</body>
</html>