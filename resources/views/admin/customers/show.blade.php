@extends('layouts.app')
@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')
@section('page-subtitle', 'Informasi Lengkap Data Pelanggan')

@section('content')
<div class="fade-in pt-2 space-y-5">

    {{-- Back --}}
    <div>
        <a href="{{ route('admin.customers.index') }}"
           class="flex gap-3 pt-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Daftar Pelanggan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ===== KOLOM KIRI ===== --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Kartu Profil --}}
            <div class="card text-center relative overflow-hidden">
                {{-- Dekorasi background --}}
                <div class="absolute inset-x-0 top-0 h-24 rounded-t-2xl" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"></div>

                <div class="relative pt-10 pb-2">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-xl ring-4 ring-white"
                         style="background:linear-gradient(135deg,#5b21b6,#7c3aed)">
                        <span class="text-white text-3xl font-bold">
                            {{ strtoupper(substr($customer->user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>

                <div class="pb-5 px-2">
                    <h2 class="text-lg font-bold text-slate-800 mt-2">{{ $customer->user->name }}</h2>
                    <p class="text-sm text-slate-400 mb-3">{{ $customer->user->email }}</p>

                    @php $hasTransaction = $customer->transactions()->exists(); @endphp
                    @if($hasTransaction)
                        <span class="badge" style="background-color:#d1fae5;color:#065f46">
                            <i class="fas fa-check-circle text-xs"></i> Pelanggan Aktif
                        </span>
                    @else
                        <span class="badge" style="background-color:#f1f5f9;color:#64748b">
                            <i class="fas fa-clock text-xs"></i> Belum Bertransaksi
                        </span>
                    @endif
                </div>
            </div>

            {{-- Statistik Singkat --}}
            <div class="card space-y-3">
                <h3 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-purple-500"></i>
                    Statistik Transaksi
                </h3>
                @php
                    $totalTrx   = $customer->transactions()->count();
                    $totalBayar = $customer->transactions()->where('payment_status','paid')->sum('total_price');
                    $trxAktif   = $customer->transactions()->whereNotIn('status',['diambil'])->count();
                @endphp

                <div class="flex items-center justify-between p-3.5 rounded-xl" style="background:#f5f3ff">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#7c3aed">
                            <i class="fas fa-receipt text-white text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-600 font-medium">Total Transaksi</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#7c3aed">{{ $totalTrx }}</span>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl" style="background:#f0fdf4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#10b981">
                            <i class="fas fa-money-bill-wave text-white text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-600 font-medium">Total Dibayar</span>
                    </div>
                    <span class="font-bold text-sm" style="color:#059669">Rp {{ number_format($totalBayar,0,',','.') }}</span>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl" style="background:#eff6ff">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#3b82f6">
                            <i class="fas fa-spinner text-white text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-600 font-medium">Transaksi Aktif</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#3b82f6">{{ $trxAktif }}</span>
                </div>

                {{-- Terdaftar sejak --}}
                <div class="pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-400 mb-1 flex items-center gap-1.5">
                        <i class="fas fa-calendar-alt"></i> Terdaftar Sejak
                    </p>
                    <p class="text-sm font-semibold text-slate-700">
                        {{ $customer->user->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ===== KOLOM KANAN ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Informasi Akun --}}
            <div class="card">
                <h3 class="font-bold text-slate-800 text-sm mb-5 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#ede9fe">
                        <i class="fas fa-user-circle text-xs" style="color:#7c3aed"></i>
                    </div>
                    Informasi Akun
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="p-3.5 rounded-xl" style="background:#f8fafc;border:1px solid #f1f5f9">
                        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
                            <i class="fas fa-user text-xs"></i> Nama Lengkap
                        </p>
                        <p class="font-semibold text-slate-900">{{ $customer->user->name }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl" style="background:#f8fafc;border:1px solid #f1f5f9">
                        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
                            <i class="fas fa-envelope text-xs"></i> Email
                        </p>
                        <p class="font-semibold text-slate-900 truncate">{{ $customer->user->email }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0">
                        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
                            <i class="fab fa-whatsapp text-green-500 text-xs"></i> No. WhatsApp
                        </p>
                        <p class="font-semibold text-slate-900">{{ $customer->phone }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl" style="background:#f5f3ff;border:1px solid #ddd6fe">
                        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
                            <i class="fas fa-user-tag text-xs"></i> Role
                        </p>
                        <span class="badge" style="background-color:#ede9fe;color:#6d28d9">
                            <i class="fas fa-user-tag text-xs"></i> Customer
                        </span>
                    </div>

                    <div class="sm:col-span-2 p-3.5 rounded-xl" style="background:#fff7ed;border:1px solid #fed7aa">
                        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-red-400 text-xs"></i> Alamat
                        </p>
                        <p class="font-semibold text-slate-900 leading-relaxed">{{ $customer->address }}</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Password --}}
            <div class="card">
                <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#fef3c7">
                        <i class="fas fa-key text-xs" style="color:#d97706"></i>
                    </div>
                    Informasi Password
                </h3>

                <div class="p-4 rounded-xl border-2" style="background:#fffbeb;border-color:#fde68a">
                    <div class="flex items-start gap-3 mb-5 p-3 rounded-xl" style="background:#fef9c3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#f59e0b">
                            <i class="fas fa-shield-alt text-white text-xs"></i>
                        </div>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            Password disimpan dalam bentuk <strong>terenkripsi (bcrypt hash)</strong>.
                            Klik ikon mata untuk melihat/menyembunyikan. Jaga kerahasiaan informasi ini.
                        </p>
                    </div>

                    @if(isset($customer->user->plain_password) && $customer->user->plain_password)
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-slate-500 mb-2 flex items-center gap-1.5">
                            <i class="fas fa-unlock-alt text-amber-500"></i> Password Asli
                        </p>
                        <div class="flex items-center gap-2">
                            <input type="password"
                                   id="plainPassword"
                                   value="{{ $customer->user->plain_password }}"
                                   readonly
                                   class="flex-1 px-4 py-2.5 rounded-xl border border-amber-200 bg-white text-sm font-mono text-slate-700 focus:outline-none focus:border-amber-400">
                            <button type="button"
                                    onclick="togglePassword('plainPassword','eyePlain')"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all flex-shrink-0"
                                    style="background:#fef3c7;color:#d97706"
                                    onmouseover="this.style.background='#fde68a'"
                                    onmouseout="this.style.background='#fef3c7'">
                                <i id="eyePlain" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-2 flex items-center gap-1.5">
                            <i class="fas fa-lock text-slate-400"></i> Password Hash (Bcrypt)
                        </p>
                        <div class="flex items-center gap-2">
                            <input type="password"
                                   id="hashPassword"
                                   value="{{ $customer->user->password }}"
                                   readonly
                                   class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-mono text-slate-500 focus:outline-none focus:border-slate-300">
                            <button type="button"
                                    onclick="togglePassword('hashPassword','eyeHash')"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all flex-shrink-0"
                                    style="background:#f1f5f9;color:#64748b"
                                    onmouseover="this.style.background='#e2e8f0'"
                                    onmouseout="this.style.background='#f1f5f9'">
                                <i id="eyeHash" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Transaksi --}}
            <div class="card">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                            <i class="fas fa-history text-xs" style="color:#3b82f6"></i>
                        </div>
                        Riwayat Transaksi
                    </h3>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#eff6ff;color:#3b82f6">
                        {{ $customer->transactions()->count() }} Transaksi
                    </span>
                </div>

                @php $transactions = $customer->transactions()->with('service')->latest()->get(); @endphp

                @if($transactions->isEmpty())
                <div class="text-center py-10">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background:#f1f5f9">
                        <i class="fas fa-receipt text-slate-300 text-2xl"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Belum Ada Transaksi</p>
                    <p class="text-slate-300 text-xs mt-1">Pelanggan ini belum pernah bertransaksi</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="table-head">
                                <th class="px-3 py-3 text-left rounded-l-xl">Invoice</th>
                                <th class="px-3 py-3 text-left">Layanan</th>
                                <th class="px-3 py-3 text-left">Total</th>
                                <th class="px-3 py-3 text-left">Status Cucian</th>
                                <th class="px-3 py-3 text-left">Bayar</th>
                                <th class="px-3 py-3 text-left rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transactions as $trx)
                            @php
                                $sHex = match($trx->status) {
                                    'antrian'      => '#f59e0b',
                                    'dicuci'       => '#3b82f6',
                                    'disetrika'    => '#7c3aed',
                                    'siap diambil' => '#10b981',
                                    'diambil'      => '#64748b',
                                    default        => '#64748b'
                                };
                                $sBg = match($trx->status) {
                                    'antrian'      => '#fef3c7',
                                    'dicuci'       => '#dbeafe',
                                    'disetrika'    => '#ede9fe',
                                    'siap diambil' => '#d1fae5',
                                    'diambil'      => '#f1f5f9',
                                    default        => '#f1f5f9'
                                };
                                $sIcon = match($trx->status) {
                                    'antrian'      => 'clock',
                                    'dicuci'       => 'soap',
                                    'disetrika'    => 'wind',
                                    'siap diambil' => 'box-open',
                                    'diambil'      => 'flag-checkered',
                                    default        => 'circle'
                                };
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-3">
                                    <span class="font-mono font-bold text-blue-600 text-xs bg-blue-50 px-2 py-1 rounded-lg">
                                        {{ $trx->invoice_code }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-slate-600 capitalize text-xs font-medium">
                                    {{ $trx->service->service_name }}
                                </td>
                                <td class="px-3 py-3 font-bold text-slate-800 text-xs whitespace-nowrap">
                                    Rp {{ number_format($trx->total_price,0,',','.') }}
                                </td>
                                <td class="px-3 py-3">
                                    <span class="badge text-xs" style="background-color:{{ $sBg }};color:{{ $sHex }}">
                                        <i class="fas fa-{{ $sIcon }} text-xs"></i>
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    @if($trx->payment_status === 'paid')
                                        <span class="badge text-xs" style="background:#d1fae5;color:#065f46">
                                            <i class="fas fa-check text-xs"></i> Lunas
                                        </span>
                                    @else
                                        <span class="badge text-xs" style="background:#ffedd5;color:#7c2d12">
                                            <i class="fas fa-clock text-xs"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.transactions.show', ['id' => $trx->id]) }}"
                                           class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors"
                                           title="Detail Transaksi">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        @if($trx->status === 'diambil')
                                        <form action="{{ route('admin.transactions.destroy', ['id' => $trx->id]) }}"
                                              method="POST"
                                              data-confirm-title="Hapus Transaksi?"
                                              data-confirm-message="Transaksi {{ $trx->invoice_code }} akan dihapus permanen."
                                              data-confirm-type="danger"
                                              data-confirm-ok="Hapus"
                                              data-no-loading>
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 h-7 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors"
                                                    title="Hapus Transaksi">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection