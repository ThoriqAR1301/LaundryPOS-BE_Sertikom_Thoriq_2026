@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('page-subtitle', 'Invoice : ' . $transaction->invoice_code)

@section('content')
<div class="fade-in pt-2 space-y-5">

    @php
        $statusSteps = ['antrian','dicuci','disetrika','siap diambil','diambil'];
        $currentStep = array_search($transaction->status, $statusSteps);
        $statusOptions = [
            'antrian' => ['label'=>'Antrian', 'icon'=>'🕐', 'hex'=>'#f59e0b'],
            'dicuci' => ['label'=>'Dicuci', 'icon'=>'🫧', 'hex'=>'#3b82f6'],
            'disetrika' => ['label'=>'Disetrika', 'icon'=>'♨️', 'hex'=>'#7c3aed'],
            'siap diambil' => ['label'=>'Siap Diambil', 'icon'=>'✅', 'hex'=>'#10b981'],
            'diambil' => ['label'=>'Selesai', 'icon'=>'🏁', 'hex'=>'#64748b'],
        ];
    @endphp

    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background:linear-gradient(135deg,#0c4a6e,#0369a1,#0ea5e9);border:1px solid #38bdf8">
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#7dd3fc"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background:rgba(255,255,255,0.15)">
            <i class="fas fa-file-invoice text-white text-xl"></i>
        </div>
        <div class="flex-1 relative z-10">
            <p class="text-sky-200 text-xs font-semibold uppercase tracking-wider mb-0.5">Detail Transaksi</p>
            <p class="text-white font-bold text-xl font-mono">{{ $transaction->invoice_code }}</p>
            <p class="text-sky-300 text-xs mt-0.5">
                <i class="fas fa-user text-xs mr-1"></i>{{ $transaction->customer->user->name }} &nbsp;·&nbsp;
                <i class="fas fa-calendar text-xs mr-1"></i>{{ $transaction->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <div class="relative z-10 text-right hidden sm:block">
            @php
                $bHex = match($transaction->status) {
                    'antrian' => '#fbbf24',
                    'dicuci' => '#60a5fa',
                    'disetrika' => '#c084fc',
                    'siap diambil' => '#34d399',
                    'diambil' => '#94a3b8',
                    default => '#94a3b8'
                };
                $bIcon = match($transaction->status) {
                    'antrian' => 'clock',
                    'dicuci' => 'soap',
                    'disetrika' => 'wind',
                    'siap diambil' => 'box-open',
                    'diambil' => 'flag-checkered',
                    default => 'circle'
                };
            @endphp
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold" style="background:rgba(255,255,255,0.15);color:{{ $bHex }}">
                <i class="fas fa-{{ $bIcon }}"></i>
                {{ ucfirst($transaction->status) }}
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                <i class="fas fa-tasks text-xs" style="color:#3b82f6"></i>
            </div>
            Progress Cucian
        </h3>

        @php
            $statusStyles = [
                'antrian' => ['gradient'=>'linear-gradient(135deg,#f59e0b,#d97706)', 'icon'=>'fa-clock', 'color'=>'#f59e0b', 'label'=>'Antrian'],
                'dicuci' => ['gradient'=>'linear-gradient(135deg,#3b82f6,#0ea5e9)', 'icon'=>'fa-soap', 'color'=>'#3b82f6', 'label'=>'Dicuci'],
                'disetrika' => ['gradient'=>'linear-gradient(135deg,#7c3aed,#a855f7)', 'icon'=>'fa-wind', 'color'=>'#7c3aed', 'label'=>'Disetrika'],
                'siap diambil' => ['gradient'=>'linear-gradient(135deg,#10b981,#059669)', 'icon'=>'fa-box-open', 'color'=>'#10b981', 'label'=>'Siap Ambil'],
                'diambil' => ['gradient'=>'linear-gradient(135deg,#64748b,#475569)', 'icon'=>'fa-flag-checkered','color'=>'#64748b', 'label'=>'Selesai'],
            ];
            $statusSteps = ['antrian','dicuci','disetrika','siap diambil','diambil'];
            $currentStep = array_search($transaction->status, $statusSteps);
        @endphp

        <div class="relative flex items-start justify-between px-4">

            <div class="absolute h-0.5 bg-slate-200 dark:bg-slate-700 rounded-full" style="top:28px;left:calc(10% + 28px);right:calc(10% + 28px);z-index:0"></div>

            @if($currentStep > 0)
            <div class="absolute h-0.5 rounded-full transition-all duration-700" style="top:28px;left:calc(10% + 28px);width:calc((100% - 20% - 56px) * {{ $currentStep / (count($statusSteps)-1) }});background:linear-gradient(to right,#f59e0b,#3b82f6,#7c3aed,#10b981);z-index:0"></div>
            @endif

            @foreach($statusSteps as $i => $step)
            @php $st = $statusStyles[$step]; $done = $i < $currentStep; $active = $i == $currentStep; @endphp
            <div class="flex flex-col items-center gap-2" style="z-index:1;width:20%">

                <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-md transition-all duration-300" style="background:{{ ($done || $active) ? $st['gradient'] : '#e2e8f0' }}; {{ $active ? 'box-shadow:0 0 0 4px rgba(0,0,0,0.06),0 0 16px ' . $st['color'] . '55' : '' }}">
                    <i class="fas {{ $st['icon'] }} text-lg" style="color:{{ ($done || $active) ? '#fff' : '#94a3b8' }}"></i>
                </div>

                <span class="text-xl font-extrabold leading-none" style="color:{{ ($done || $active) ? $st['color'] : '#94a3b8' }}">
                    {{ $active ? '●' : ($done ? '✓' : '0') }}
                </span>

                <span class="text-xs font-semibold text-center leading-tight text-slate-500 dark:text-slate-400">
                    {{ $st['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#e0f2fe">
                    <i class="fas fa-receipt text-xs" style="color:#0369a1"></i>
                </div>
                Info Transaksi
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Invoice</span>
                    <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg text-xs">
                        {{ $transaction->invoice_code }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Tanggal</span>
                    <span class="font-medium text-slate-700">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Layanan</span>
                    <span class="font-semibold text-slate-700 capitalize">{{ $transaction->service->service_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Jumlah</span>
                    <span class="font-semibold text-slate-700">
                        {{ $transaction->service_unit != 0 ? (floatval($transaction->service_unit) == intval($transaction->service_unit) ? intval($transaction->service_unit) : floatval($transaction->service_unit)) : $transaction->service_unit }} {{ $transaction->service->unit }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Total Harga</span>
                    <span class="font-bold text-slate-800 text-base">
                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Pembayaran</span>
                    <span class="badge {{ $transaction->payment_method == 'cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $transaction->payment_method == 'cash' ? '💵 Cash' : '🏦 Transfer' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-500">Status Bayar</span>
                    @if($transaction->payment_status === 'paid')
                        <span class="badge bg-emerald-100 text-emerald-700">
                            <i class="fas fa-check text-xs"></i> Lunas
                        </span>
                    @else
                        <span class="badge bg-orange-100 text-orange-700">
                            <i class="fas fa-clock text-xs"></i> Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#ede9fe">
                    <i class="fas fa-user text-xs" style="color:#7c3aed"></i>
                </div>
                Info Pelanggan
            </h3>

            <div class="flex items-center gap-4 p-4 rounded-2xl mb-3" style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);border:1px solid #ddd6fe">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
                    <span class="text-white font-bold text-xl">
                        {{ strtoupper(substr($transaction->customer->user->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-900 text-base truncate">{{ $transaction->customer->user->name }}</p>
                    <p class="text-slate-500 text-xs truncate mt-0.5">{{ $transaction->customer->user->email }}</p>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-1" style="background:#ede9fe;color:#6d28d9">
                        <i class="fas fa-user-tag text-xs"></i> Customer
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#10b981">
                        <i class="fab fa-whatsapp text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">No. WhatsApp</p>
                        <p class="font-semibold text-slate-900 text-sm">{{ $transaction->customer->phone }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 px-4 py-3 rounded-xl" style="background:#fff7ed;border:1px solid #fed7aa">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#f97316">
                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">Alamat</p>
                        <p class="font-semibold text-slate-900 text-sm leading-relaxed">{{ $transaction->customer->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#d1fae5">
                <i class="fas fa-sync text-xs" style="color:#10b981"></i>
            </div>
            Update Status Cucian
        </h3>
        <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST" class="flex flex-wrap gap-3 items-end">
            @csrf @method('PUT')
            <div class="flex-1 min-w-48">
                <label class="form-label text-xs text-slate-400 mb-1">Pilih Status Baru</label>
                <div class="relative">
                    <i class="fas fa-tshirt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="status" class="form-input pl-10 pr-10 appearance-none cursor-pointer font-semibold">
                        @foreach($statusOptions as $val => $opt)
                        <option value="{{ $val }}" {{ $transaction->status == $val ? 'selected' : '' }}>
                            {{ $opt['icon'] }} {{ $opt['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-sync"></i> Update Status
            </button>
        </form>
    </div>

    @if($transaction->payment_method === 'transfer')
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                <i class="fas fa-image text-xs" style="color:#3b82f6"></i>
            </div>
            Bukti Pembayaran Transfer
        </h3>

        @if($transaction->payment_proof)
        <div class="mb-4 p-4 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0">
            <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Bukti Bayar" class="rounded-xl border border-slate-200 max-w-xs shadow-sm mb-3">
            @php
                try {
                    $paidAtText = $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d M Y H:i') : '-';
                } catch (\Exception $e) {
                    $paidAtText = '-';
                }
            @endphp
            <p class="text-emerald-700 text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                Sudah Dibayar Pada {{ $paidAtText }}
            </p>
        </div>
        @endif

        <form action="{{ route('admin.transactions.payment-proof', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div class="flex-1 min-w-48">
                <label class="form-label">
                    {{ $transaction->payment_proof ? 'Ganti' : 'Upload' }} Bukti Transfer
                </label>
                <input type="file" name="payment_proof" accept="image/*" class="form-input">
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-upload"></i> Upload
            </button>
        </form>
    </div>
    @endif

    @if($transaction->cloth_photo)
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#fef3c7">
                <i class="fas fa-camera text-xs" style="color:#d97706"></i>
            </div>
            Foto Kondisi Baju Masuk
        </h3>
        <div class="p-4 rounded-xl" style="background:#fffbeb;border:1px solid #fde68a">
            <img src="{{ Storage::url($transaction->cloth_photo) }}" alt="Foto Kondisi Baju" class="rounded-xl border border-amber-200 max-w-xs shadow-sm mb-3 cursor-pointer" onclick="document.getElementById('clothModal').classList.remove('hidden')">
            <p class="text-amber-700 text-xs flex items-center gap-1.5">
                <i class="fas fa-info-circle"></i>
                Foto Diambil Saat Baju Masuk Untuk Menghindari Komplain
            </p>
            <p class="text-amber-500 text-xs mt-1 flex items-center gap-1.5">
                <i class="fas fa-hand-pointer"></i>
                Klik Foto Untuk Memperbesar
            </p>
        </div>
    </div>

    <div id="clothModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.8)" onclick="this.classList.add('hidden')">
        <img src="{{ Storage::url($transaction->cloth_photo) }}" alt="Foto Kondisi Baju" class="max-w-full max-h-full rounded-2xl shadow-2xl">
        <button class="absolute top-4 right-4 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <div id="strukModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" style="animation:popIn 0.3s cubic-bezier(0.34,1.56,0.64,1)">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#d1fae5">
                        <i class="fas fa-receipt text-sm" style="color:#059669"></i>
                    </div>
                    <p class="font-bold text-slate-800">Struk Transaksi</p>
                </div>
                <button onclick="tutupStruk()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div id="strukContent" class="px-5 py-5">
                <div class="text-center mb-4 pb-4 border-b-2 border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">
                        <i class="fas fa-shirt text-white text-lg"></i>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">LaundryPOS</p>
                    <p class="text-slate-500 text-xs">Sistem Manajemen Laundry</p>
                </div>

                <div class="text-center mb-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">No. Invoice</p>
                    <p class="font-mono font-bold text-blue-600 text-xl">{{ $transaction->invoice_code }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="border-t border-dashed border-slate-200 mb-4"></div>

                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pelanggan</p>
                    <p class="font-semibold text-slate-800">{{ $transaction->customer->user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $transaction->customer->phone }}</p>
                </div>

                <div class="border-t border-dashed border-slate-200 mb-4"></div>

                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Detail Layanan</p>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-slate-600 text-sm capitalize">{{ $transaction->service->service_name }}</span>
                        <span class="text-slate-600 text-sm">{{ $transaction->service_unit != 0 ? (floatval($transaction->service_unit) == intval($transaction->service_unit) ? intval($transaction->service_unit) : floatval($transaction->service_unit)) : $transaction->service_unit }} {{ $transaction->service->unit }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-slate-500 text-xs">Harga Per {{ $transaction->service->unit }}</span>
                        <span class="text-slate-500 text-xs">Rp {{ number_format($transaction->service->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-slate-500 text-xs">Metode Bayar</span>
                        <span class="text-slate-600 text-xs font-semibold uppercase">{{ $transaction->payment_method }}</span>
                    </div>
                </div>

                <div class="border-t-2 border-dashed border-slate-300 mb-4"></div>

                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-slate-800">TOTAL</span>
                    <span class="font-bold text-xl" style="color:#059669">
                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-center mb-4">
                    @if($transaction->payment_status === 'paid')
                    <span class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold" style="background:#d1fae5;color:#065f46">
                        <i class="fas fa-check-circle"></i> LUNAS
                    </span>
                    @else
                    <span class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold" style="background:#ffedd5;color:#7c2d12">
                        <i class="fas fa-clock"></i> BELUM LUNAS
                    </span>
                    @endif
                </div>

                <div class="border-t border-dashed border-slate-200 mb-4"></div>

                <div class="text-center mb-2">
                    <p class="text-xs text-slate-400 mb-1">Status Cucian</p>
                    <p class="font-semibold text-slate-700 capitalize">{{ $transaction->status }}</p>
                </div>

                <div class="text-center mt-4 pt-4 border-t border-dashed border-slate-200">
                    <p class="text-xs text-slate-400">Terima Kasih Telah Mempercayakan</p>
                    <p class="text-xs text-slate-400">Cucian Anda Kepada Kami 🙏</p>
                    <p class="text-xs text-slate-300 mt-2">Dicetak : {{ now()->format('d M Y, H:i') }}</p>
                </div>

            </div>
            
            <div class="flex gap-2 px-5 py-4 border-t border-slate-100">
                <button onclick="printStruk()" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5" style="background:linear-gradient(135deg,#059669,#10b981);box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('admin.transactions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        
        <button onclick="cetakStruk()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5" style="background:linear-gradient(135deg,#059669,#10b981);box-shadow:0 4px 12px rgba(16,185,129,0.3)">
            <i class="fas fa-print"></i> Cetak Struk
        </button>
        <button onclick="downloadPDFFromShow()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5" style="background:linear-gradient(135deg,#dc2626,#ef4444);box-shadow:0 4px 12px rgba(239,68,68,0.3)">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>

</div>

@push('scripts')
<script>
function cetakStruk() {
    document.getElementById('strukModal').classList.remove('hidden');
}

function tutupStruk() {
    document.getElementById('strukModal').classList.add('hidden');
}

function printStruk() {
    const content = document.getElementById('strukContent').innerHTML;
    const win = window.open('', '_blank', 'width=400,height=700');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Struk - {{ $transaction->invoice_code }}</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <style>
                * { margin:0; padding:0; box-sizing:border-box; }
                body { font-family: 'Courier New', monospace; font-size: 12px; padding: 16px; max-width: 300px; margin: 0 auto; }
                @media print {
                    body { padding: 0; }
                    button { display: none !important; }
                }
            </style>
        </head>
        <body>${content}<br><button onclick="window.print()" style="width:100%;padding:8px;margin-top:10px;background:#059669;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:13px">🖨️ Print Struk</button></body>
        </html>
    `);
    win.document.close();
}

function loadHtml2PDF(callback) {
    if (window.html2pdf) { callback(); return; }
    const s = document.createElement('script');
    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
    s.onload = callback;
    document.head.appendChild(s);
}

function downloadPDFFromShow() {
    const content = document.getElementById('strukContent');
    if (!content) {
        alert('Konten Struk Tidak Ditemukan. Coba Buka Modal Cetak Struk Dulu');
        return;
    }

    if (typeof LaundryToast !== 'undefined') {
        LaundryToast.warning('Menyiapkan PDF...', 'Mohon Tunggu Sebentar');
    }

    function doDownload() {
        const opt = {
            margin: [4, 4, 4, 4],
            filename: 'Struk-{{ $transaction->invoice_code }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false },
            jsPDF: { unit: 'mm', format: [90, 200], orientation: 'portrait' }
        };

        const clone = content.cloneNode(true);
        clone.style.cssText = 'background:#fff;padding:16px;font-family:sans-serif;width:280px';
        document.body.appendChild(clone);

        html2pdf().set(opt).from(clone).save().then(() => {
            document.body.removeChild(clone);
            if (typeof LaundryToast !== 'undefined') {
                LaundryToast.success('PDF Berhasil Diunduh!', 'File Tersimpan Di Folder Downloads');
            }
        });
    }

    if (window.html2pdf) {
        doDownload();
    } else {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
        script.onload = doDownload;
        script.onerror = () => alert('Gagal Memuat Library PDF. Periksa Koneksi Internet Anda');
        document.head.appendChild(script);
    }
}


document.getElementById('strukModal').addEventListener('click', function(e) {
    if (e.target === this) tutupStruk();
});
</script>
@endpush
@endsection