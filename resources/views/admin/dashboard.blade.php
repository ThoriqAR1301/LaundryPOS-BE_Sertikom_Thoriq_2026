@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
<div class="space-y-6 fade-in pt-2">


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/25">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <span class="text-blue-200 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-blue-100 text-sm font-medium">Total Pendapatan</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</p>
            <p class="text-blue-200 text-xs mt-2"><i class="fas fa-arrow-up mr-1"></i>Hari Ini : Rp {{ number_format($data['pendapatan_hari_ini'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg shadow-cyan-500/25">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
                <span class="text-cyan-100 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-cyan-100 text-sm font-medium">Total Transaksi</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($data['total_transaksi']) }}</p>
            <p class="text-cyan-200 text-xs mt-2"><i class="fas fa-calendar-day mr-1"></i>Hari Ini : {{ $data['transaksi_hari_ini'] }} Transaksi</p>
        </div>
        <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg shadow-violet-500/25">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <span class="text-violet-100 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-violet-100 text-sm font-medium">Total Pelanggan</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($data['total_customer']) }}</p>
            <p class="text-violet-200 text-xs mt-2"><i class="fas fa-user-plus mr-1"></i>Pelanggan Terdaftar</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg shadow-amber-500/25">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <span class="text-amber-100 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-amber-100 text-sm font-medium">Total Layanan</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($data['total_layanan']) }}</p>
            <p class="text-amber-200 text-xs mt-2"><i class="fas fa-cog mr-1"></i>Layanan Aktif</p>
        </div>
    </div>


    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $statuses = [
                ['label'=>'Antrian', 'value'=>$data['antrian'], 'icon'=>'clock', 'hex'=>'#f59e0b'],
                ['label'=>'Diproses', 'value'=>$data['diproses'], 'icon'=>'soap', 'hex'=>'#3b82f6'],
                ['label'=>'Siap Ambil', 'value'=>$data['siap'], 'icon'=>'check-circle', 'hex'=>'#10b981'],
                ['label'=>'Selesai', 'value'=>$data['selesai'], 'icon'=>'flag-checkered', 'hex'=>'#64748b'],
            ];
        @endphp
        @foreach($statuses as $s)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4" style="border-left: 4px solid {{ $s['hex'] }}">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ $s['hex'] }}20">
                <i class="fas fa-{{ $s['icon'] }} text-lg" style="color: {{ $s['hex'] }}"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-medium">{{ $s['label'] }}</p>
                <p class="text-2xl font-bold" style="color: {{ $s['hex'] }}">{{ $s['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-slate-800">Pendapatan Bulanan</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Tahun {{ date('Y') }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-500 text-sm"></i>
                </div>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
                <a href="{{ route('admin.transactions.index') }}" class="text-blue-500 text-xs font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($data['transaksi_terbaru'] as $trx)
                @php
                    $trxHex = match($trx->status) {
                        'antrian' => '#f59e0b',
                        'dicuci' => '#3b82f6',
                        'disetrika' => '#7c3aed',
                        'siap diambil' => '#10b981',
                        'diambil' => '#64748b',
                        default => '#64748b'
                    };
                    $trxBg = match($trx->status) {
                        'antrian' => '#fef3c7',
                        'dicuci' => '#dbeafe',
                        'disetrika' => '#ede9fe',
                        'siap diambil' => '#d1fae5',
                        'diambil' => '#f1f5f9',
                        default => '#f1f5f9'
                    };
                @endphp
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-blue-50 transition-colors">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($trx->customer->user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $trx->customer->user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $trx->invoice_code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-700">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                        <span class="badge text-xs" style="background-color: {{ $trxBg }}; color: {{ $trxHex }}">{{ $trx->status }}</span>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">Belum Ada Transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($data['pendapatan_bulanan']),
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection