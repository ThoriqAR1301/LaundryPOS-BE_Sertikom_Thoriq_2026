@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan & Analitik')
@section('page-subtitle', 'Statistik Bisnis Laundry Anda')

@section('content')
<div class="fade-in pt-2 space-y-6">


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <span class="text-blue-200 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-blue-100 text-sm">Total Pendapatan</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                <span class="text-cyan-100 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-cyan-100 text-sm">Total Transaksi</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($totalTransaksi) }}</p>
        </div>
        <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-violet-100 text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-violet-100 text-sm">Total Pelanggan</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($totalCustomer) }}</p>
        </div>
    </div>


    <div class="card">
        <h3 class="font-bold text-slate-800 text-sm mb-4">Filter Laporan</h3>
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="form-label"><i class="fas fa-calendar text-blue-400 mr-1"></i> Tahun</label>
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
                <label class="form-label"><i class="fas fa-calendar-week text-cyan-400 mr-1"></i> Bulan (Chart Harian)</label>
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
            <button type="submit" class="btn-primary"><i class="fas fa-chart-bar"></i> Tampilkan</button>
        </form>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Pendapatan Per Bulan ({{ $year }})</h3>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-bar text-blue-500 text-sm"></i>
                </div>
            </div>
            <canvas id="monthlyChart" height="160"></canvas>
        </div>
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Transaksi Harian — {{ $bulanList[$month-1] }} {{ $year }}</h3>
                <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-cyan-500 text-sm"></i>
                </div>
            </div>
            <canvas id="dailyChart" height="160"></canvas>
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
                        <th class="px-4 py-3 text-left rounded-l-xl">#</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Total Order</th>
                        <th class="px-4 py-3 text-left rounded-r-xl">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($layananPopuler as $i => $l)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color: #dbeafe">
                                    <i class="fas fa-soap text-xs" style="color: #3b82f6"></i>
                                </div>
                                <span class="font-semibold text-slate-700 capitalize">{{ $l->service->service_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af">{{ $l->total_order }} Order</span>
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}</td>
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

@push('scripts')
<script>
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan',
                data: @json($pendapatanBulanan),
                backgroundColor: 'rgba(59,130,246,0.15)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.raw) } } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } }, x: { grid: { display: false } } }
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
                backgroundColor: 'rgba(6,182,212,0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });
</script>
@endpush
@endsection