@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan & Analitik')
@section('page-subtitle', 'Statistik Bisnis Laundry Anda')

@section('content')
<div class="fade-in pt-2 space-y-6">


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg" style="background:linear-gradient(135deg,#1d4ed8,#2563eb,#0ea5e9)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#38bdf8"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <p class="text-blue-100 text-sm font-medium">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <p class="text-blue-200 text-xs mt-2 flex items-center gap-1">
                    <i class="fas fa-check-circle text-emerald-300"></i>
                    Dari Transaksi Lunas
                </p>
            </div>

        </div>

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg" style="background:linear-gradient(135deg,#0891b2,#06b6d4,#0284c7)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#67e8f9"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                </div>
                <p class="text-cyan-100 text-sm font-medium">Total Transaksi</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalTransaksi) }}</p>
                <p class="text-cyan-200 text-xs mt-2 flex items-center gap-1">
                    <i class="fas fa-receipt"></i>
                    Semua Status Transaksi
                </p>
            </div>

        </div>

        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg" style="background:linear-gradient(135deg,#6d28d9,#7c3aed,#a855f7)">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#c084fc"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-violet-100 text-sm font-medium">Total Pelanggan</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalCustomer) }}</p>
                <p class="text-violet-200 text-xs mt-2 flex items-center gap-1">
                    <i class="fas fa-user-check"></i>
                    Pelanggan Terdaftar
                </p>
            </div>

        </div>
    </div>


    <div class="card">
        <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#eff6ff">
                <i class="fas fa-filter text-blue-500 text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Filter Laporan</h3>
                <p class="text-xs text-slate-400">Pilih Tahun Dan Bulan Untuk Menampilkan Data</p>
            </div>
        </div>
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="form-label">
                    <i class="fas fa-calendar text-blue-400 mr-1"></i> Tahun
                </label>
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="year" class="form-input pl-10 pr-10 appearance-none cursor-pointer">
                        @for($y = date('Y'); $y >= date('Y')-3; $y--)
                        <option value="{{ $y }}" {{ $year==$y ? 'selected' : '' }}>📅 {{ $y }}</option>
                        @endfor
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label class="form-label">
                    <i class="fas fa-calendar-week text-cyan-400 mr-1"></i> Bulan (Chart Harian)
                </label>
                <div class="relative">
                    <i class="fas fa-calendar-day absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="month" class="form-input pl-10 pr-10 appearance-none cursor-pointer">
                        @php $bulanList = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; @endphp
                        @foreach($bulanList as $i => $b)
                        <option value="{{ $i+1 }}" {{ $month==$i+1 ? 'selected' : '' }}>🗓️ {{ $b }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-chart-bar"></i> Tampilkan
            </button>
        </form>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#eff6ff">
                        <i class="fas fa-chart-bar text-blue-500 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Pendapatan Per Bulan</h3>
                        <p class="text-xs text-slate-400">Tahun {{ $year }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <div class="w-3 h-3 rounded-full" style="background:#3b82f6"></div>
                    Pendapatan
                </div>
            </div>
            <canvas id="monthlyChart" height="160"></canvas>
        </div>

        <div class="card">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#ecfeff">
                        <i class="fas fa-chart-line text-cyan-500 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Transaksi Harian</h3>
                        <p class="text-xs text-slate-400">{{ $bulanList[$month-1] }} {{ $year }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <div class="w-3 h-3 rounded-full" style="background:#06b6d4"></div>
                    Transaksi
                </div>
            </div>
            <canvas id="dailyChart" height="160"></canvas>
        </div>
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
                    @forelse($layananPopuler as $i => $l)
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

@push('scripts')
<script>
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan',
                data: @json($pendapatanBulanan),
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
                tooltip: { callbacks: { label: c => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.raw) } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    border: { dash: [4,4] },
                    ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                },
                x: { grid: { display: false } }
            }
        }
    });

    const dailyData = @json($transaksiHarian);
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
            plugins: { legend: { display: false }, tooltip: { mode:'index', intersect:false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#f1f5f9' },
                    border: { dash: [4,4] }
                },
                x: { grid: { display: false } }
            },
            interaction: { mode: 'index', intersect: false },
        }
    });
</script>
@endpush
@endsection