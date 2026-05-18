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
            @php
                /*
                 * $data['pendapatan_hari_ini'] = SUM total_price WHERE payment_status='paid'
                 * AND DATE(created_at) = today
                 * Di Controller:
                 * 'pendapatan_hari_ini' => Transaction::where('payment_status','paid')
                 *     ->whereDate('created_at', today())->sum('total_price'),
                 */
                $hariIni = (float) ($data['pendapatan_hari_ini'] ?? 0);
            @endphp
            <p class="text-blue-200 text-xs mt-2 flex items-center gap-1">
                @if($hariIni > 0)
                    <i class="fas fa-arrow-up text-emerald-300"></i>
                    <span>Hari Ini :</span>
                    <span class="text-emerald-300 font-bold">+ Rp {{ number_format($hariIni, 0, ',', '.') }}</span>
                @else
                    <i class="fas fa-minus opacity-60"></i>
                    <span>Hari Ini : Rp 0</span>
                @endif
            </p>
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
            <p class="text-cyan-200 text-xs mt-2">
                <i class="fas fa-calendar-day mr-1"></i>
                Hari Ini : {{ $data['transaksi_hari_ini'] }} Transaksi
            </p>
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
            <p class="text-violet-200 text-xs mt-2">
                <i class="fas fa-user-plus mr-1"></i>Pelanggan Terdaftar
            </p>
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
            <p class="text-amber-200 text-xs mt-2">
                <i class="fas fa-cog mr-1"></i>Layanan Aktif
            </p>
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

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Transaksi</h3>
                <p class="text-slate-400 text-sm mt-0.5">Total {{ $data['transactions']->total() }} Transaksi</p>
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
                        <th class="px-4 py-3 text-left">Status Bayar</th>
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
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2 py-1 rounded-lg">
                                {{ $trx->invoice_code }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-slate-700">{{ $trx->customer->user->name }}</p>
                            <p class="text-slate-400 text-xs">{{ $trx->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 capitalize">{{ $trx->service->service_name }}</td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color:{{ $sBg }}; color:{{ $sHex }}">
                                <i class="fas fa-{{ $sIcon }} text-xs"></i> {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($trx->payment_status === 'paid')
                                <span class="badge" style="background-color:#d1fae5; color:#065f46">
                                    <i class="fas fa-check text-xs"></i> Lunas
                                </span>
                            @else
                                <span class="badge" style="background-color:#ffedd5; color:#7c2d12">
                                    <i class="fas fa-clock text-xs"></i> Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-receipt text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Belum Ada Transaksi</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-slate-800">Transaksi Harian</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Bulan {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-cyan-500 text-sm"></i>
                </div>
            </div>
            <canvas id="dailyChart" height="120"></canvas>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 text-sm mb-5">Status Pembayaran</h3>
            <div class="space-y-3">

                <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color:#d1fae5; border-color:#10b981">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color:#10b981">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold" style="color:#065f46">Lunas</p>
                        <p class="text-3xl font-bold" style="color:#064e3b">{{ $data['summary']['lunas'] ?? 0 }}</p>
                    </div>
                    <i class="fas fa-check-circle text-3xl opacity-20" style="color:#10b981"></i>
                </div>

                <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color:#ffedd5; border-color:#f97316">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color:#f97316">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold" style="color:#7c2d12">Pending</p>
                        <p class="text-3xl font-bold" style="color:#431407">{{ $data['summary']['pending'] ?? 0 }}</p>
                    </div>
                    <i class="fas fa-hourglass-half text-3xl opacity-20" style="color:#f97316"></i>
                </div>

                <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color:#dbeafe; border-color:#3b82f6">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color:#3b82f6">
                        <i class="fas fa-receipt text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold" style="color:#1e3a8a">Total Transaksi</p>
                        <p class="text-3xl font-bold" style="color:#1e3a8a">{{ $data['summary']['total'] ?? 0 }}</p>
                    </div>
                    <i class="fas fa-receipt text-3xl opacity-20" style="color:#3b82f6"></i>
                </div>

            </div>
        </div>
    </div>

    <div class="gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Pendapatan Per Bulan ({{ date('Y') }})</h3>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-bar text-blue-500 text-sm"></i>
                </div>
            </div>
            <canvas id="monthlyChart" height="160"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-slate-800">Layanan Terpopuler</h3>
            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-amber-500 text-sm"></i>
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
                        <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                                     style="background-color:#dbeafe">
                                    <i class="fas fa-soap text-xs" style="color:#3b82f6"></i>
                                </div>
                                <span class="font-semibold text-slate-700 capitalize">
                                    {{ $l->service->service_name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color:#dbeafe; color:#1e40af">
                                {{ $l->total_order }} Order
                            </span>
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">
                            Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">Belum Ada Data</td>
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
    const dailyData   = @json($data['transaksi_harian'] ?? []);
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
                backgroundColor: 'rgba(6,182,212,0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan',
                data: @json($data['pendapatan_bulanan']),
                backgroundColor: 'rgba(59,130,246,0.15)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                borderRadius: 8,
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
                    ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
