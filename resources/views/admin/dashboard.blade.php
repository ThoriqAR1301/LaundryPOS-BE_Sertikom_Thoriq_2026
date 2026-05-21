@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
<div class="space-y-5 fade-in pt-2">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
             style="background:linear-gradient(135deg,#1d4ed8,#2563eb,#0ea5e9)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#38bdf8"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <p class="text-blue-100 text-sm font-medium">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</p>
                @php $hariIni = (float)($data['pendapatan_hari_ini'] ?? 0); @endphp
                <div class="mt-2 flex items-center gap-1.5 text-xs">
                    @if($hariIni > 0)
                        <div class="flex items-center gap-1 px-2 py-0.5 rounded-full" style="background:rgba(52,211,153,0.2)">
                            <i class="fas fa-arrow-up text-emerald-300 text-xs"></i>
                            <span class="text-emerald-300 font-bold">+ Rp {{ number_format($hariIni,0,',','.') }}</span>
                        </div>
                        <span class="text-blue-200">Hari Ini</span>
                    @else
                        <span class="text-blue-300 opacity-70">Hari ini : Rp 0</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
             style="background:linear-gradient(135deg,#0891b2,#06b6d4,#0284c7)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#67e8f9"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                </div>
                <p class="text-cyan-100 text-sm font-medium">Total Transaksi</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($data['total_transaksi']) }}</p>
                <div class="mt-2 flex items-center gap-1.5 text-xs text-cyan-200">
                    <i class="fas fa-calendar-day"></i>
                    <span>Hari ini : <strong class="text-white">{{ $data['transaksi_hari_ini'] }}</strong> Transaksi</span>
                </div>

            </div>

        </div>

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
             style="background:linear-gradient(135deg,#6d28d9,#7c3aed,#a855f7)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#c084fc"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-violet-100 text-sm font-medium">Total Pelanggan</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($data['total_customer']) }}</p>
                <div class="mt-2 flex items-center gap-1.5 text-xs text-violet-200">
                    <i class="fas fa-user-check"></i>
                    <span>Pelanggan Terdaftar</span>
                </div>

            </div>

        </div>

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
             style="background:linear-gradient(135deg,#b45309,#d97706,#f59e0b)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#fcd34d"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-tags text-xl"></i>
                    </div>
                </div>
                <p class="text-amber-100 text-sm font-medium">Total Layanan</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($data['total_layanan']) }}</p>
                <div class="mt-2 flex items-center gap-1.5 text-xs text-amber-200">
                    <i class="fas fa-cog"></i>
                    <span>Layanan Aktif Tersedia</span>
                </div>

            </div>

        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#eff6ff">
                    <i class="fas fa-tshirt text-blue-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Status Cucian Saat Ini</h3>
                    <p class="text-xs text-slate-400">Rekap Semua Antrian Aktif</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statuses = [
                    ['label'=>'Antrian', 'value'=>$data['antrian'], 'icon'=>'clock', 'hex'=>'#f59e0b', 'bg'=>'#fef3c7', 'desc'=>'Menunggu Proses'],
                    ['label'=>'Diproses', 'value'=>$data['diproses'], 'icon'=>'soap', 'hex'=>'#3b82f6', 'bg'=>'#dbeafe', 'desc'=>'Sedang Dicuci/Setrika'],
                    ['label'=>'Siap Ambil', 'value'=>$data['siap'], 'icon'=>'box-open', 'hex'=>'#10b981', 'bg'=>'#d1fae5', 'desc'=>'Siap Diambil'],
                    ['label'=>'Selesai', 'value'=>$data['selesai'], 'icon'=>'flag-checkered', 'hex'=>'#64748b', 'bg'=>'#f1f5f9', 'desc'=>'Sudah Diambil'],
                ];
            @endphp
            @foreach($statuses as $s)
            <div class="rounded-2xl p-4 border-2 transition-all hover:shadow-md" style="background:{{ $s['bg'] }};border-color:{{ $s['hex'] }}30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $s['hex'] }}">
                        <i class="fas fa-{{ $s['icon'] }} text-white text-sm"></i>
                    </div>
                    <span class="text-3xl font-bold" style="color:{{ $s['hex'] }}">{{ $s['value'] }}</span>
                </div>
                <p class="font-bold text-sm" style="color:{{ $s['hex'] }}">{{ $s['label'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#eff6ff">
                    <i class="fas fa-list-alt text-blue-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Transaksi Terkini</h3>
                    <p class="text-xs text-slate-400">Total {{ $data['transactions']->total() }} Transaksi</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="px-4 py-3 text-left rounded-l-xl">Invoice</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Status Cucian</th>
                        <th class="px-4 py-3 text-left rounded-r-xl">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data['transactions'] as $trx)
                    @php
                        $sHex = match($trx->status) {
                            'antrian' => '#f59e0b',
                            'dicuci' => '#3b82f6',
                            'disetrika' => '#7c3aed',
                            'siap diambil' => '#10b981',
                            'diambil' => '#64748b',
                            default => '#64748b',
                        };
                        $sBg = match($trx->status) {
                            'antrian' => '#fef3c7',
                            'dicuci' => '#dbeafe',
                            'disetrika' => '#ede9fe',
                            'siap diambil' => '#d1fae5',
                            'diambil' => '#f1f5f9',
                            default => '#f1f5f9',
                        };
                        $sIcon = match($trx->status) {
                            'antrian' => 'clock',
                            'dicuci' => 'soap',
                            'disetrika' => 'wind',
                            'siap diambil' => 'box-open',
                            'diambil' => 'flag-checkered',
                            default => 'circle',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-4 py-3.5">
                            <a href="{{ route('admin.transactions.show', ['id' => $trx->id]) }}" class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100 transition-colors">
                                {{ $trx->invoice_code }}
                            </a>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-white" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
                                    {{ strtoupper(substr($trx->customer->user->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 text-xs">{{ $trx->customer->user->name }}</p>
                                    <p class="text-slate-400 text-xs">{{ $trx->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 capitalize text-xs font-medium">
                            {{ $trx->service->service_name }}
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-800 text-xs whitespace-nowrap">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color:{{ $sBg }};color:{{ $sHex }}">
                                <i class="fas fa-{{ $sIcon }} text-xs"></i> {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($trx->payment_status === 'paid')
                                <span class="badge" style="background-color:#d1fae5;color:#065f46">
                                    <i class="fas fa-check text-xs"></i> Lunas
                                </span>
                            @else
                                <span class="badge" style="background-color:#ffedd5;color:#7c2d12">
                                    <i class="fas fa-clock text-xs"></i> Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#f1f5f9">
                                    <i class="fas fa-receipt text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium text-sm">Belum Ada Transaksi</p>
                                <a href="{{ route('admin.transactions.create') }}" class="btn-primary text-xs">
                                    <i class="fas fa-plus"></i> Buat Transaksi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data['transactions']->hasPages())
        <div class="mt-5 flex justify-center">
            {{ $data['transactions']->links() }}
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#ecfeff">
                        <i class="fas fa-chart-line text-cyan-500 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Transaksi Harian</h3>
                        <p class="text-xs text-slate-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <div class="w-3 h-3 rounded-full" style="background:#06b6d4"></div>
                    Transaksi
                </div>
            </div>
            <canvas id="dailyChart" height="110"></canvas>
        </div>

        <div class="card flex flex-col gap-4">
            <div class="flex items-center gap-2.5 mb-1">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#f0fdf4">
                    <i class="fas fa-credit-card text-green-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Status Pembayaran</h3>
                    <p class="text-xs text-slate-400">Ringkasan Pembayaran</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl p-4 border-2" style="background:#d1fae5;border-color:#10b981">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:#10b981">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color:#065f46">Lunas</p>
                    <p class="text-3xl font-bold" style="color:#064e3b">{{ $data['summary']['lunas'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl opacity-20" style="color:#10b981"></i>
            </div>

            <div class="flex items-center gap-3 rounded-2xl p-4 border-2" style="background:#ffedd5;border-color:#f97316">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:#f97316">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color:#7c2d12">Pending</p>
                    <p class="text-3xl font-bold" style="color:#431407">{{ $data['summary']['pending'] ?? 0 }}</p>
                </div>
                <i class="fas fa-hourglass-half text-3xl opacity-20" style="color:#f97316"></i>
            </div>

            <div class="flex items-center gap-3 rounded-2xl p-4 border-2" style="background:#dbeafe;border-color:#3b82f6">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:#3b82f6">
                    <i class="fas fa-receipt text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color:#1e3a8a">Total</p>
                    <p class="text-3xl font-bold" style="color:#1e3a8a">{{ $data['summary']['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-receipt text-3xl opacity-20" style="color:#3b82f6"></i>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#eff6ff">
                    <i class="fas fa-chart-bar text-blue-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Pendapatan Per Bulan</h3>
                    <p class="text-xs text-slate-400">Tahun {{ date('Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-slate-400">
                <div class="w-3 h-3 rounded-full" style="background:#3b82f6"></div>
                Pendapatan
            </div>

        </div>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#fffbeb">
                    <i class="fas fa-star text-amber-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Layanan Terpopuler</h3>
                    <p class="text-xs text-slate-400">Berdasarkan Total Order</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="px-4 py-3 text-left rounded-l-xl">No</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Total Order</th>
                        <th class="px-4 py-3 text-left rounded-r-xl">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data['layanan_populer'] as $i => $l)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold text-white" style="background:{{ $i===0 ? '#f59e0b' : ($i===1 ? '#94a3b8' : ($i===2 ? '#b45309' : '#cbd5e1')) }}">
                                {{ $i + 1 }}
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#dbeafe">
                                    <i class="fas fa-soap text-xs" style="color:#3b82f6"></i>
                                </div>
                                <span class="font-semibold text-slate-700 capitalize">
                                    {{ $l->service->service_name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background:#dbeafe;color:#1e40af">
                                <i class="fas fa-shopping-cart text-xs"></i>
                                {{ $l->total_order }} Order
                            </span>
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">
                            Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#f1f5f9">
                                    <i class="fas fa-star text-slate-300 text-lg"></i>
                                </div>
                                <p class="text-slate-400 text-sm">Belum Ada Data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const dailyData = @json($data['transaksi_harian'] ?? []);
    const dailyLabels = dailyData.map(d => 'Tgl ' + d.hari);
    const dailyValues = dailyData.map(d => d.total);

    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dailyLabels.length ? dailyLabels : ['Tidak Ada Data'],
            datasets: [{
                label: 'Transaksi',
                data: dailyValues.length ? dailyValues : [0],
                borderColor: '#06b6d4',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                    g.addColorStop(0, 'rgba(6,182,212,0.2)');
                    g.addColorStop(1, 'rgba(6,182,212,0.01)');
                    return g;
                },
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#06b6d4',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { mode:'index', intersect: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' }, border:{ dash:[4,4] } },
                x: { grid: { display: false } }
            },
            interaction: { mode: 'index', intersect: false },
        }
    });

    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan',
                data: @json($data['pendapatan_bulanan']),
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                    g.addColorStop(0, 'rgba(59,130,246,0.8)');
                    g.addColorStop(1, 'rgba(6,182,212,0.3)');
                    return g;
                },
                borderColor: 'transparent',
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: c => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.raw) }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    border: { dash:[4,4] },
                    ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
