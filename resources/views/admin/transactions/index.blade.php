@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', 'Manajemen Transaksi')
@section('page-subtitle', 'Kelola Semua Transaksi Laundry')

@section('content')
<div class="fade-in pt-2 space-y-5">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cardStatuses = [
                ['key'=>'antrian', 'label'=>'Antrian', 'icon'=>'clock', 'desc'=>'Menunggu Diproses', 'hex'=>'#f59e0b'],
                ['key'=>'dicuci', 'label'=>'Dicuci', 'icon'=>'soap', 'desc'=>'Sedang Dicuci', 'hex'=>'#3b82f6'],
                ['key'=>'disetrika', 'label'=>'Disetrika', 'icon'=>'wind', 'desc'=>'Sedang Disetrika', 'hex'=>'#7c3aed'],
                ['key'=>'siap diambil','label'=>'Siap Ambil', 'icon'=>'check-circle', 'desc'=>'Siap Diambil Pelanggan', 'hex'=>'#10b981'],
            ];
        @endphp
        @foreach($cardStatuses as $s)
        @php $pct = $summary['total'] > 0 ? round($summary[$s['key']] / $summary['total'] * 100) : 0; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6" style="border-top: 4px solid {{ $s['hex'] }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $s['hex'] }}">
                    <i class="fas fa-{{ $s['icon'] }} text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: {{ $s['hex'] }}">{{ $summary[$s['key']] }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">{{ $s['label'] }}</p>
            <p class="text-xs text-slate-400 mb-3">{{ $s['desc'] }}</p>
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $s['hex'] }}"></div>
            </div>
            <p class="text-xs font-semibold mt-1.5 text-right" style="color: {{ $s['hex'] }}">{{ $pct }}% Dari Total</p>
        </div>
        @endforeach
    </div>

    <div class="card">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="form-label">Cari</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Invoice..." class="form-input pl-10">
                </div>
            </div>
            <div class="min-w-48">
                <label class="form-label">Status Cucian</label>
                <div class="relative">
                    <i class="fas fa-tshirt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="status" class="form-input pl-10 pr-10 appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach([
                            'antrian' => ['label'=>'Antrian', 'icon'=>'🕐'],
                            'dicuci' => ['label'=>'Dicuci', 'icon'=>'🫧'],
                            'disetrika' => ['label'=>'Disetrika', 'icon'=>'♨️'],
                            'siap diambil' => ['label'=>'Siap Diambil','icon'=>'✅'],
                            'diambil' => ['label'=>'Selesai', 'icon'=>'🏁'],
                        ] as $val => $opt)
                        <option value="{{ $val }}" {{ request('status')==$val ? 'selected' : '' }}>
                            {{ $opt['icon'] }} {{ $opt['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div class="min-w-44">
                <label class="form-label">Status Bayar</label>
                <div class="relative">
                    <i class="fas fa-credit-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="payment_status" class="form-input pl-10 pr-10 appearance-none cursor-pointer">
                        <option value="">Semua Pembayaran</option>
                        <option value="pending" {{ request('payment_status')=='pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="paid"    {{ request('payment_status')=='paid'    ? 'selected' : '' }}>✅ Lunas</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn-secondary"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <p class="text-slate-500 text-sm">Menampilkan <span class="font-bold text-dark px-2 py-1 bg-slate-200 rounded-lg">{{ $transactions->total() }}</span> Transaksi</p>
            <a href="{{ route('admin.transactions.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Transaksi Baru
            </a>
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
                        <th class="px-4 py-3 text-left rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                    @php
                        $statusHex = match($trx->status) {
                            'antrian' => '#f59e0b',
                            'dicuci' => '#3b82f6',
                            'disetrika' => '#7c3aed',
                            'siap diambil' => '#10b981',
                            'diambil' => '#64748b',
                            default => '#64748b'
                        };
                        $statusBg = match($trx->status) {
                            'antrian' => '#fef3c7',
                            'dicuci' => '#dbeafe',
                            'disetrika' => '#ede9fe',
                            'siap diambil' => '#d1fae5',
                            'diambil' => '#f1f5f9',
                            default => '#f1f5f9'
                        };
                        $statusIcon = match($trx->status) {
                            'antrian' => 'clock',
                            'dicuci' => 'soap',
                            'disetrika' => 'wind',
                            'siap diambil' => 'box-open',
                            'diambil' => 'flag-checkered',
                            default => 'circle'
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2 py-1 rounded-lg">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-slate-700">{{ $trx->customer->user->name }}</p>
                            <p class="text-slate-400 text-xs">{{ $trx->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 capitalize">{{ $trx->service->service_name }}</td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color: {{ $statusBg }}; color: {{ $statusHex }}">
                                <i class="fas fa-{{ $statusIcon }} text-xs"></i>
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($trx->payment_status == 'paid')
                                <span class="badge" style="background-color: #d1fae5; color: #065f46">
                                    <i class="fas fa-check text-xs"></i> Lunas
                                </span>
                            @else
                                <span class="badge" style="background-color: #ffedd5; color: #7c2d12">
                                    <i class="fas fa-clock text-xs"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.transactions.show', $trx->id) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors inline-flex" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($trx->status === 'diambil')
                                <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin Hapus Transaksi {{ $trx->invoice_code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
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
        @if($transactions->hasPages())
        <div class="mt-5 flex justify-center">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>


    <div class="card overflow-hidden p-0">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Ringkasan Status Transaksi</h3>
            <span class="text-xs text-slate-400">Total {{ $summary['total'] }} Transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Jumlah</th>
                        <th class="px-5 py-3 text-center">Persentase</th>
                        <th class="px-5 py-3 text-left">Proporsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $tableStatuses = [
                            ['key'=>'antrian', 'label'=>'Antrian', 'icon'=>'clock', 'hex'=>'#f59e0b'],
                            ['key'=>'dicuci', 'label'=>'Dicuci', 'icon'=>'soap', 'hex'=>'#3b82f6'],
                            ['key'=>'disetrika', 'label'=>'Disetrika', 'icon'=>'wind', 'hex'=>'#7c3aed'],
                            ['key'=>'siap diambil','label'=>'Siap Diambil','icon'=>'check-circle', 'hex'=>'#10b981'],
                            ['key'=>'diambil', 'label'=>'Selesai', 'icon'=>'flag-checkered', 'hex'=>'#64748b'],
                        ];
                    @endphp
                    @foreach($tableStatuses as $s)
                    @php $pct = $summary['total'] > 0 ? round($summary[$s['key']] / $summary['total'] * 100) : 0; @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: {{ $s['hex'] }}">
                                    <i class="fas fa-{{ $s['icon'] }} text-white text-xs"></i>
                                </div>
                                <span class="font-semibold text-slate-700">{{ $s['label'] }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="text-lg font-bold" style="color: {{ $s['hex'] }}">{{ $summary[$s['key']] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex flex-col items-center leading-tight">
                                <span class="text-lg font-bold" style="color: {{ $s['hex'] }}">{{ $pct }}</span>
                                <span class="text-xs font-medium text-slate-400">%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 w-56">
                            <div class="w-full bg-slate-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%; background-color: {{ $s['hex'] }}"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="card">
        <h3 class="font-bold text-slate-800 text-sm mb-6">Alur Status Cucian</h3>
        <div class="flex items-start justify-between relative">
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-slate-300 z-0 mx-10"></div>
            @php
                $timelineStatuses = [
                    ['key'=>'antrian', 'label'=>'Antrian', 'icon'=>'clock', 'hex'=>'#f59e0b'],
                    ['key'=>'dicuci', 'label'=>'Dicuci', 'icon'=>'soap', 'hex'=>'#3b82f6'],
                    ['key'=>'disetrika', 'label'=>'Disetrika', 'icon'=>'wind', 'hex'=>'#7c3aed'],
                    ['key'=>'siap diambil','label'=>'Siap Ambil', 'icon'=>'box-open', 'hex'=>'#10b981'],
                    ['key'=>'diambil', 'label'=>'Selesai', 'icon'=>'flag-checkered', 'hex'=>'#64748b'],
                ];
            @endphp
            @foreach($timelineStatuses as $s)
            <div class="flex flex-col items-center gap-2 z-10 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg" style="background-color: {{ $s['hex'] }}">
                    <i class="fas fa-{{ $s['icon'] }} text-white text-sm"></i>
                </div>
                <span class="text-2xl font-bold" style="color: {{ $s['hex'] }}">{{ $summary[$s['key']] }}</span>
                <span class="text-xs font-semibold text-slate-600 text-center">{{ $s['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>


    <div class="card">
        <h3 class="font-bold text-slate-800 text-sm mb-4">Status Pembayaran</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color: #d1fae5; border-color: #10b981">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #10b981">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color: #065f46">Lunas</p>
                    <p class="text-3xl font-bold" style="color: #064e3b">{{ $summary['lunas'] }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl opacity-20" style="color: #10b981"></i>
            </div>
            <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color: #ffedd5; border-color: #f97316">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #f97316">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color: #7c2d12">Pending</p>
                    <p class="text-3xl font-bold" style="color: #431407">{{ $summary['pending'] }}</p>
                </div>
                <i class="fas fa-hourglass-half text-3xl opacity-20" style="color: #f97316"></i>
            </div>
            <div class="flex items-center gap-4 rounded-2xl px-5 py-4 border-2" style="background-color: #dbeafe; border-color: #3b82f6">
                <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #3b82f6">
                    <i class="fas fa-receipt text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold" style="color: #1e3a8a">Total Transaksi</p>
                    <p class="text-3xl font-bold" style="color: #1e3a8a">{{ $summary['total'] }}</p>
                </div>
                <i class="fas fa-receipt text-3xl opacity-20" style="color: #3b82f6"></i>
            </div>
        </div>
    </div>

</div>
@endsection