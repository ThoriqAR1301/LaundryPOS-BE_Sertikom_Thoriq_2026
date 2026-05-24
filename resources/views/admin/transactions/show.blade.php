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
        <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                <i class="fas fa-tasks text-xs" style="color:#3b82f6"></i>
            </div>
            Progress Cucian
        </h3>
        <div class="relative px-2 pb-4 pt-2">

            <div class="absolute h-1 bg-slate-200 rounded-full" style="top:28px;left:calc(10% + 16px);right:calc(10% + 16px);z-index:0"></div>

            <div class="absolute h-1 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full transition-all duration-500" style="top:28px;left:calc(10% + 16px);width:calc((100% - 20% - 32px) * {{ $currentStep / (count($statusSteps)-1) }});z-index:0"></div>

            <div class="flex items-start justify-between px-0">
                @foreach($statusSteps as $i => $step)
                <div class="flex flex-col items-center gap-2" style="z-index:1;width:20%">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center border-2 transition-all {{ $i <= $currentStep ? 'bg-gradient-to-br from-blue-400 to-cyan-400 border-blue-400 text-white' : 'bg-white border-slate-200 text-slate-300' }}">
                        @if($i < $currentStep)
                            <i class="fas fa-check text-sm"></i>
                        @elseif($i == $currentStep)
                            <i class="fas fa-circle text-sm animate-pulse"></i>
                        @else
                            <i class="fas fa-circle text-sm"></i>
                        @endif
                    </div>
                    <span class="text-sm font-semibold text-center w-full leading-tight {{ $i <= $currentStep ? 'text-blue-600' : 'text-slate-400' }}">
                        {{ ucfirst($step) }}
                    </span>
                </div>
                @endforeach
            </div>
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
                        {{ $transaction->service_unit }} {{ $transaction->service->unit }}
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
            <div class="flex items-center gap-5 p-5 rounded-xl mb-4" style="background:#f5f3ff">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 shadow" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
                    <span class="text-slate-800 font-bold text-lg">
                        {{ strtoupper(substr($transaction->customer->user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-bold text-slate-900">{{ $transaction->customer->user->name }}</p>
                    <p class="text-slate-900 text-xs">{{ $transaction->customer->user->email }}</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-5 p-5 rounded-xl" style="background:#f8fafc">
                    <i class="fas fa-phone text-slate-400 w-4 flex-shrink-0"></i>
                    <span class="text-slate-900 font-medium">{{ $transaction->customer->phone }}</span>
                </div>
                <div class="flex items-start gap-5 p-5 rounded-xl" style="background:#f8fafc">
                    <i class="fas fa-map-marker-alt text-slate-400 w-4 flex-shrink-0 mt-0.5"></i>
                    <span class="text-slate-900 font-medium leading-relaxed">{{ $transaction->customer->address }}</span>
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

    <div class="flex gap-12 flex-wrap">
        <a href="{{ route('admin.transactions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @if(!in_array($transaction->status, ['siap diambil', 'diambil']))
        <a href="{{ route('admin.transactions.edit', ['id' => $transaction->id]) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Edit Transaksi
        </a>
        @endif
    </div>

</div>
@endsection