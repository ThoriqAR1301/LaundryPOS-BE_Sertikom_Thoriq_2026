@extends('layouts.app')
@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')
@section('page-subtitle', 'Ubah Data Transaksi ' . $transaction->invoice_code)

@section('content')
<div class="fade-in pt-2 space-y-5">

    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background:linear-gradient(135deg,#78350f,#b45309,#d97706);border:1px solid #f59e0b">
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#fcd34d"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
             style="background:rgba(255,255,255,0.15)">
            <i class="fas fa-file-invoice text-white text-xl"></i>
        </div>

        <div class="flex-1 relative z-10">
            <p class="text-amber-200 text-xs font-semibold uppercase tracking-wider mb-0.5">Transaksi</p>
            <p class="text-white font-bold text-lg font-mono">{{ $transaction->invoice_code }}</p>
            <p class="text-amber-300 text-xs mt-0.5">
                <i class="fas fa-user text-xs mr-1"></i>{{ $transaction->customer->user->name }}
                &nbsp;·&nbsp;
                <i class="fas fa-calendar text-xs mr-1"></i>{{ $transaction->created_at->format('d M Y') }}
            </p>
        </div>

        <div class="relative z-10 text-right">
            @php
                $sHex = match($transaction->status) {
                    'antrian' => '#fbbf24',
                    'dicuci' => '#60a5fa',
                    'disetrika' => '#c084fc',
                    'siap diambil' => '#34d399',
                    'diambil' => '#94a3b8',
                    default => '#94a3b8'
                };
                $sIcon = match($transaction->status) {
                    'antrian' => 'clock',
                    'dicuci' => 'soap',
                    'disetrika' => 'wind',
                    'siap diambil' => 'box-open',
                    'diambil' => 'flag-checkered',
                    default => 'circle'
                };
            @endphp
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold" style="background:rgba(255,255,255,0.15);color:{{ $sHex }}">
                <i class="fas fa-{{ $sIcon }}"></i>
                {{ ucfirst($transaction->status) }}
            </div>

        </div>
    </div>


    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fef3c7">
                <i class="fas fa-edit" style="color:#d97706"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Edit Transaksi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Ubah Data Yang Diperlukan Lalu Klik Simpan</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl border flex items-start gap-3" style="background:#fef2f2;border-color:#fecaca">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#ef4444">
                <i class="fas fa-exclamation text-white text-xs"></i>
            </div>
            <div>
                <p class="text-sm font-semibold mb-1" style="color:#991b1b">Terdapat Kesalahan :</p>
                <ul class="text-xs space-y-0.5 list-disc list-inside" style="color:#b91c1c">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.transactions.update', ['id' => $transaction->id]) }}" method="POST" class="space-y-5" id="editTrxForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                <div>
                    <label class="form-label">
                        <i class="fas fa-user text-blue-400 mr-1.5"></i> Pelanggan
                    </label>
                    <input type="hidden" name="customer_id" value="{{ $transaction->customer_id }}">

                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 cursor-not-allowed">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-sm text-white" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
                            {{ strtoupper(substr($transaction->customer->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-700 text-sm truncate">{{ $transaction->customer->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $transaction->customer->phone }}</p>
                        </div>
                        <i class="fas fa-lock text-slate-300 text-sm"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Pelanggan Tidak Dapat Diubah Setelah Transaksi Dibuat
                    </p>
                </div>


                <div>
                    <label class="form-label">
                        <i class="fas fa-tags text-cyan-400 mr-1.5"></i> Layanan
                    </label>
                    <div class="relative">
                        <i class="fas fa-concierge-bell absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="service_id" class="form-input pl-10 pr-10 appearance-none cursor-pointer" id="serviceSelect" onchange="hitungTotal()" required>
                            <option value="">— Pilih Layanan —</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-unit="{{ $s->unit }}" {{ (old('service_id', $transaction->service_id) == $s->id) ? 'selected' : '' }}>
                                {{ ucfirst($s->service_name) }} — Rp {{ number_format($s->price, 0, ',', '.') }}/{{ $s->unit }}
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Harga Akan Dihitung Ulang Otomatis
                    </p>
                </div>

            </div>


            <div>
                <label class="form-label">
                    <i class="fas fa-weight text-violet-400 mr-1.5"></i> Berat / Jumlah
                </label>
                <div class="relative">
                    <i class="fas fa-balance-scale absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="number" name="service_unit" id="weight" value="{{ old('service_unit', $transaction->service_unit) }}" placeholder="Contoh : 5" min="0.1" step="0.1" onkeyup="hitungTotal()" oninput="hitungTotal()" class="form-input pl-10 pr-20" required>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg text-xs font-bold" style="background:#ede9fe;color:#7c3aed" id="unitLabel">Unit</div>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Masukkan Berat (Kg) Atau Jumlah (Pcs) Sesuai Layanan
                </p>
            </div>

            <div class="rounded-xl p-4 border" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fde68a">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#f59e0b">
                            <i class="fas fa-calculator text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-amber-700 font-medium">Estimasi Total Harga Baru</p>
                            <p class="text-xs text-amber-500 line-through">
                                Harga Lama : Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <span class="font-bold text-xl text-amber-700" id="totalPreview">
                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>


            <div>
                <label class="form-label">
                    <i class="fas fa-wallet text-emerald-400 mr-1.5"></i> Metode Pembayaran
                </label>
                <div class="grid grid-cols-2 gap-3" id="paymentMethods">
                    <label class="payment-label flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all" onclick="selectPayment(this)">
                        <input type="radio" name="payment_method" value="cash" class="accent-blue-500 hidden" {{ old('payment_method','cash')=='cash' ? 'checked' : '' }}>
                        <div>
                            <i class="fas fa-money-bill-wave text-emerald-500 mb-1 text-lg"></i>
                            <p class="method-label font-semibold text-sm text-slate-400 transition-colors">Cash</p>
                            <p class="text-slate-400 text-xs">Bayar Tunai</p>
                        </div>
                    </label>
                    <label class="payment-label flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all" onclick="selectPayment(this)">
                        <input type="radio" name="payment_method" value="transfer" class="accent-blue-500 hidden" {{ old('payment_method')=='transfer' ? 'checked' : '' }}>
                        <div>
                            <i class="fas fa-university text-blue-500 mb-1 text-lg"></i>
                            <p class="method-label font-semibold text-sm text-slate-400 transition-colors">Transfer</p>
                            <p class="text-slate-400 text-xs">Transfer Bank</p>
                        </div>
                    </label>
                </div>
            </div>
 
            @if($transaction->payment_status === 'paid')
            <div class="p-4 rounded-xl flex items-start gap-3" style="background:#f0fdf4;border:1px solid #bbf7d0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#10b981">
                    <i class="fas fa-check text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color:#065f46">Transaksi Ini Sudah Lunas</p>
                    <p class="text-xs mt-0.5" style="color:#047857">
                        Status Pembayaran Tidak Akan Berubah Meskipun Metode Pembayaran Diedit
                    </p>
                </div>
            </div>
            @endif
 
            <div class="flex gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
 
</div>
 
@push('scripts')
<script>
const colorMap = {
    cash: { border:'#10b981', bg:'#f0fdf4' },
    transfer: { border:'#3b82f6', bg:'#eff6ff'  },
};
 
function hitungTotal() {
    const select = document.getElementById('serviceSelect');
    const opt = select.options[select.selectedIndex];
    const price = parseFloat(opt?.dataset?.price) || 0;
    const unit = opt?.dataset?.unit || 'Unit';
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const total = price * weight;
    document.getElementById('unitLabel').textContent = unit;
    document.getElementById('totalPreview').textContent =
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}
 
function selectPayment(el) {
    document.querySelectorAll('.payment-label').forEach(l => {
        l.style.borderColor = '#e2e8f0';
        l.style.background = '';
        l.querySelector('.method-label').style.color = '#94a3b8';
    });
    el.style.borderColor = '#3b82f6';
    el.style.background = '#eff6ff';
    el.querySelector('.method-label').style.color = '#1e293b';
    el.querySelector('input[type=radio]').checked = true;
}
 
window.addEventListener('DOMContentLoaded', () => {
    const checkedInput = document.querySelector('.payment-label input:checked');
    if (checkedInput) selectPayment(checkedInput.closest('.payment-label'), checkedInput.value);
    hitungTotal();
});
</script>
@endpush
@endsection