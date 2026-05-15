@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Baru')
@section('page-subtitle', 'Input Data Transaksi Laundry')

@section('content')
<div class="max-w-2xl fade-in pt-2">
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-6">Form Transaksi Baru</h3>
        <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-5" id="trxForm">
            @csrf

            {{-- Dropdown Pelanggan --}}
            <div>
                <label class="form-label"><i class="fas fa-user text-blue-400 mr-1"></i> Pelanggan</label>
                <div class="relative">
                    <i class="fas fa-users absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="customer_id" class="form-input pl-10 pr-10 appearance-none cursor-pointer" required>
                        <option value="">— Pilih Pelanggan —</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>
                            {{ $c->user->name }} — {{ $c->phone }}
                        </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Dropdown Layanan --}}
            <div>
                <label class="form-label"><i class="fas fa-tags text-cyan-400 mr-1"></i> Layanan</label>
                <div class="relative">
                    <i class="fas fa-concierge-bell absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="service_id" class="form-input pl-10 pr-10 appearance-none cursor-pointer" id="serviceSelect" onchange="hitungTotal()" required>
                        <option value="">— Pilih Layanan —</option>
                        @foreach($services as $s)
                        <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-unit="{{ $s->unit }}" {{ old('service_id')==$s->id ? 'selected' : '' }}>
                            {{ ucfirst($s->service_name) }} — Rp {{ number_format($s->price, 0, ',', '.') }}/{{ $s->unit }}
                        </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Berat --}}
            <div>
                <label class="form-label"><i class="fas fa-weight text-violet-400 mr-1"></i> Berat / Jumlah</label>
                <div class="relative">
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}" placeholder="0" min="0.1" step="0.1" onkeyup="hitungTotal()" class="form-input pr-16">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold" id="unitLabel">Unit</span>
                </div>
            </div>

            {{-- Preview Total --}}
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 text-sm font-medium">Estimasi Total Harga :</span>
                    <span class="text-blue-600 font-bold text-lg" id="totalPreview">Rp 0</span>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="form-label"><i class="fas fa-wallet text-emerald-400 mr-1"></i> Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="payment_method" value="cash" class="accent-blue-500" {{ old('payment_method','cash')=='cash' ? 'checked' : '' }}>
                        <div>
                            <i class="fas fa-money-bill-wave text-emerald-500 mb-1"></i>
                            <p class="font-semibold text-slate-700 text-sm">Cash</p>
                            <p class="text-slate-400 text-xs">Bayar Tunai</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="payment_method" value="transfer" class="accent-blue-500" {{ old('payment_method')=='transfer' ? 'checked' : '' }}>
                        <div>
                            <i class="fas fa-university text-blue-500 mb-1"></i>
                            <p class="font-semibold text-slate-700 text-sm">Transfer</p>
                            <p class="text-slate-400 text-xs">Transfer Bank</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Buat Transaksi</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function hitungTotal() {
        const select = document.getElementById('serviceSelect');
        const opt    = select.options[select.selectedIndex];
        const price  = parseFloat(opt.dataset.price) || 0;
        const unit   = opt.dataset.unit || 'unit';
        const weight = parseFloat(document.getElementById('weight').value) || 0;
        const total  = price * weight;
        document.getElementById('unitLabel').textContent = unit;
        document.getElementById('totalPreview').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
</script>
@endpush
@endsection