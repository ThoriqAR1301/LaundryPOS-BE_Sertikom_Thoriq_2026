@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('page-subtitle', 'Invoice : ' . $transaction->invoice_code)

@section('content')
<div class="max-w-3xl fade-in pt-2 space-y-5">

    @php
        $statusSteps = ['antrian','dicuci','disetrika','siap diambil','diambil'];
        $currentStep = array_search($transaction->status, $statusSteps);
        $statusOptions = [
            'antrian'      => ['label'=>'Antrian',     'icon'=>'🕐', 'hex'=>'#f59e0b'],
            'dicuci'       => ['label'=>'Dicuci',      'icon'=>'🫧', 'hex'=>'#3b82f6'],
            'disetrika'    => ['label'=>'Disetrika',   'icon'=>'♨️', 'hex'=>'#7c3aed'],
            'siap diambil' => ['label'=>'Siap Diambil','icon'=>'✅', 'hex'=>'#10b981'],
            'diambil'      => ['label'=>'Selesai',     'icon'=>'🏁', 'hex'=>'#64748b'],
        ];
    @endphp

    {{-- Progress Cucian --}}
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-5">Progress Cucian</h3>
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 top-5 h-1 bg-slate-100 -z-0">
                <div class="h-full bg-gradient-to-r from-blue-400 to-cyan-400 transition-all duration-500" style="width: {{ ($currentStep / (count($statusSteps)-1)) * 100 }}%"></div>
            </div>
            @foreach($statusSteps as $i => $step)
            <div class="flex flex-col items-center gap-2 z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all
                    {{ $i <= $currentStep ? 'bg-gradient-to-br from-blue-400 to-cyan-400 border-blue-400 text-white' : 'bg-white border-slate-200 text-slate-300' }}">
                    @if($i < $currentStep)
                        <i class="fas fa-check text-sm"></i>
                    @elseif($i == $currentStep)
                        <i class="fas fa-circle text-sm animate-pulse"></i>
                    @else
                        <i class="fas fa-circle text-sm"></i>
                    @endif
                </div>
                <span class="text-xs font-semibold text-center w-14 leading-tight {{ $i <= $currentStep ? 'text-blue-600' : 'text-slate-400' }}">{{ ucfirst($step) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Info Transaksi & Pelanggan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="card space-y-4">
            <h3 class="font-bold text-slate-800">Info Transaksi</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Invoice</span>
                    <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg">{{ $transaction->invoice_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal</span>
                    <span class="font-medium text-slate-700">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Layanan</span>
                    <span class="font-semibold text-slate-700 capitalize">{{ $transaction->service->service_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Harga</span>
                    <span class="font-bold text-slate-800 text-base">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pembayaran</span>
                    <span class="badge {{ $transaction->payment_method=='cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $transaction->payment_method == 'cash' ? '💵 Cash' : '🏦 Transfer' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card space-y-4">
            <h3 class="font-bold text-slate-800">Info Pelanggan</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ strtoupper(substr($transaction->customer->user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-bold text-slate-800">{{ $transaction->customer->user->name }}</p>
                    <p class="text-slate-400 text-xs">{{ $transaction->customer->user->email }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <i class="fas fa-phone text-slate-400 w-4"></i>
                    {{ $transaction->customer->phone }}
                </div>
                <div class="flex items-start gap-2 text-slate-600">
                    <i class="fas fa-map-marker-alt text-slate-400 w-4 mt-0.5"></i>
                    {{ $transaction->customer->address }}
                </div>
            </div>
        </div>
    </div>

    {{-- Update Status — Dropdown Di-upgrade --}}
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4">Update Status Cucian</h3>
        <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST" class="flex flex-wrap gap-3 items-end">
            @csrf @method('PUT')
            <div class="flex-1 min-w-48">
                <label class="form-label text-xs text-slate-400 mb-1">Status Saat Ini</label>
                <div class="relative">
                    <i class="fas fa-tshirt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="status" class="form-input pl-10 pr-10 appearance-none cursor-pointer font-semibold">
                        @foreach($statusOptions as $val => $opt)
                        <option value="{{ $val }}" {{ $transaction->status==$val ? 'selected' : '' }}>
                            {{ $opt['icon'] }} {{ $opt['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <button type="submit" class="btn-primary"><i class="fas fa-sync"></i> Update Status</button>
        </form>
    </div>

    {{-- Bukti Pembayaran --}}
    @if($transaction->payment_method == 'transfer')
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4">Bukti Pembayaran Transfer</h3>
        @if($transaction->payment_proof)
        <div class="mb-4">
            <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Bukti Bayar" class="rounded-xl border border-slate-200 max-w-xs shadow-sm">
            <p class="text-emerald-600 text-sm font-semibold mt-2 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Sudah Dibayar Pada {{ $transaction->paid_at?->format('d M Y H:i') }}
            </p>
        </div>
        @endif
        <form action="{{ route('admin.transactions.payment-proof', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div class="flex-1 min-w-48">
                <label class="form-label">{{ $transaction->payment_proof ? 'Ganti' : 'Upload' }} Bukti Transfer</label>
                <input type="file" name="payment_proof" accept="image/*" class="form-input">
            </div>
            <button type="submit" class="btn-primary"><i class="fas fa-upload"></i> Upload</button>
        </form>
    </div>
    @endif

    <div class="flex gap-3">
        <a href="{{ route('admin.transactions.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection