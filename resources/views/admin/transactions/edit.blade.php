@extends('layouts.app')
@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')
@section('page-subtitle', 'Ubah Data Transaksi ' . $transaction->invoice_code)

@section('content')
<div class="fade-in pt-2 space-y-5">

    {{-- Back --}}
    <div>
        <a href="{{ route('admin.transactions.show', ['id' => $transaction->id]) }}"
           class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Detail Transaksi
        </a>
    </div>

    {{-- Banner Info Invoice --}}
    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden"
         style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#0369a1);border:1px solid #1e40af">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-4 -bottom-6 w-20 h-20 rounded-full opacity-10" style="background:#38bdf8"></div>

        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
             style="background:rgba(255,255,255,0.15);backdrop-filter:blur(4px)">
            <i class="fas fa-file-invoice text-white text-xl"></i>
        </div>

        <div class="flex-1 relative z-10">
            <p class="text-blue-200 text-xs font-semibold mb-0.5 uppercase tracking-wider">Sedang Mengedit</p>
            <p class="text-white font-bold text-xl font-mono">{{ $transaction->invoice_code }}</p>
            <p class="text-blue-300 text-xs mt-1">
                <i class="fas fa-user text-xs mr-1"></i>
                {{ $transaction->customer->user->name }}
                &nbsp;·&nbsp;
                <i class="fas fa-calendar text-xs mr-1"></i>
                {{ $transaction->created_at->format('d M Y') }}
            </p>
        </div>

        <div class="relative z-10 text-right">
            @php
                $sHex = match($transaction->status) {
                    'antrian'      => '#fbbf24',
                    'dicuci'       => '#60a5fa',
                    'disetrika'    => '#c084fc',
                    'siap diambil' => '#34d399',
                    'diambil'      => '#94a3b8',
                    default        => '#94a3b8'
                };
                $sIcon = match($transaction->status) {
                    'antrian'      => 'clock',
                    'dicuci'       => 'soap',
                    'disetrika'    => 'wind',
                    'siap diambil' => 'box-open',
                    'diambil'      => 'flag-checkered',
                    default        => 'circle'
                };
            @endphp
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold"
                 style="background:rgba(255,255,255,0.15);color:{{ $sHex }}">
                <i class="fas fa-{{ $sIcon }}"></i>
                {{ ucfirst($transaction->status) }}
            </div>
            <p class="text-blue-300 text-xs mt-1.5">
                @if($transaction->payment_status === 'paid')
                    <i class="fas fa-check-circle text-green-400"></i> Lunas
                @else
                    <i class="fas fa-clock text-yellow-400"></i> Pending
                @endif
            </p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fef3c7">
                <i class="fas fa-edit" style="color:#d97706"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Edit Transaksi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Ubah data yang diperlukan lalu klik Simpan</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl border flex items-start gap-3"
             style="background:#fef2f2;border-color:#fecaca">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#ef4444">
                <i class="fas fa-exclamation text-white text-xs"></i>
            </div>
            <div>
                <p class="text-sm font-semibold mb-1" style="color:#991b1b">Terdapat Kesalahan:</p>
                <ul class="text-xs space-y-0.5 list-disc list-inside" style="color:#b91c1c">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.transactions.update', ['id' => $transaction->id]) }}"
              method="POST"
              class="space-y-5"
              id="editTrxForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Pelanggan --}}
                <div>
                    <label class="form-label">
                        <i class="fas fa-user text-blue-400 mr-1.5"></i> Pelanggan
                    </label>
                    <div class="relative">
                        <i class="fas fa-users absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="customer_id"
                                class="form-input pl-10 pr-10 appearance-none cursor-pointer"
                                required>
                            <option value="">— Pilih Pelanggan —</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}"
                                {{ (old('customer_id', $transaction->customer_id) == $c->id) ? 'selected' : '' }}>
                                {{ $c->user->name }} — {{ $c->phone }}
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Layanan --}}
                <div>
                    <label class="form-label">
                        <i class="fas fa-tags text-cyan-400 mr-1.5"></i> Layanan
                    </label>
                    <div class="relative">
                        <i class="fas fa-concierge-bell absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="service_id"
                                class="form-input pl-10 pr-10 appearance-none cursor-pointer"
                                id="serviceSelect"
                                onchange="hitungTotal()"
                                required>
                            <option value="">— Pilih Layanan —</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}"
                                    data-price="{{ $s->price }}"
                                    data-unit="{{ $s->unit }}"
                                    {{ (old('service_id', $transaction->service_id) == $s->id) ? 'selected' : '' }}>
                                {{ ucfirst($s->service_name) }} — Rp {{ number_format($s->price, 0, ',', '.') }}/{{ $s->unit }}
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Berat / Jumlah --}}
            <div>
                <label class="form-label">
                    <i class="fas fa-weight text-violet-400 mr-1.5"></i> Berat / Jumlah
                </label>
                <div class="relative">
                    <input type="number"
                           name="service_unit"
                           id="weight"
                           value="{{ old('service_unit', $transaction->service_unit) }}"
                           placeholder="Masukkan jumlah..."
                           min="0.1"
                           step="0.1"
                           onkeyup="hitungTotal()"
                           oninput="hitungTotal()"
                           class="form-input pr-20"
                           required>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg text-xs font-bold"
                         style="background:#ede9fe;color:#7c3aed"
                         id="unitLabel">Unit</div>
                </div>
            </div>

            {{-- Preview Total --}}
            <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1.5px solid #fde68a">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-amber-800 flex items-center gap-2">
                            <i class="fas fa-calculator text-amber-500"></i>
                            Kalkulasi Total
                        </p>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="text-center">
                                <p class="text-xs text-amber-600">Harga Lama</p>
                                <p class="font-bold text-slate-600 text-sm line-through">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <i class="fas fa-arrow-right text-amber-400 text-xs"></i>
                            <div class="text-center">
                                <p class="text-xs text-amber-600">Harga Baru</p>
                                <p class="font-bold text-amber-700 text-lg" id="totalPreview">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(245,158,11,0.15)">
                        <i class="fas fa-coins text-amber-500 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="form-label">
                    <i class="fas fa-wallet text-emerald-400 mr-1.5"></i> Metode Pembayaran
                </label>
                <div class="grid grid-cols-2 gap-3" id="paymentMethods">
                    <label class="payment-label flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-emerald-400 transition-all"
                           onclick="selectPayment(this, 'cash')">
                        <input type="radio" name="payment_method" value="cash" class="hidden"
                               {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'checked' : '' }}>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#f0fdf4">
                            <i class="fas fa-money-bill-wave text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="method-label font-bold text-sm text-slate-400 transition-colors">Cash</p>
                            <p class="text-slate-400 text-xs">Bayar Tunai</p>
                        </div>
                    </label>
                    <label class="payment-label flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all"
                           onclick="selectPayment(this, 'transfer')">
                        <input type="radio" name="payment_method" value="transfer" class="hidden"
                               {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'checked' : '' }}>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff">
                            <i class="fas fa-university text-blue-500"></i>
                        </div>
                        <div>
                            <p class="method-label font-bold text-sm text-slate-400 transition-colors">Transfer</p>
                            <p class="text-slate-400 text-xs">Transfer Bank</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Info Lunas --}}
            @if($transaction->payment_status === 'paid')
            <div class="p-4 rounded-xl flex items-start gap-3" style="background:#f0fdf4;border:1px solid #bbf7d0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#10b981">
                    <i class="fas fa-check text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color:#065f46">Transaksi Ini Sudah Lunas</p>
                    <p class="text-xs mt-0.5" style="color:#047857">
                        Status pembayaran tidak akan berubah meskipun metode pembayaran diedit.
                    </p>
                </div>
            </div>
            @endif

            {{-- Tombol Aksi --}}
            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.transactions.show', ['id' => $transaction->id]) }}"
                   class="btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    const colorMap = {
        cash:     { border:'#10b981', bg:'#f0fdf4' },
        transfer: { border:'#3b82f6', bg:'#eff6ff'  },
    };

    function hitungTotal() {
        const select = document.getElementById('serviceSelect');
        const opt    = select.options[select.selectedIndex];
        const price  = parseFloat(opt?.dataset?.price)  || 0;
        const unit   = opt?.dataset?.unit || 'Unit';
        const weight = parseFloat(document.getElementById('weight').value) || 0;
        const total  = price * weight;

        document.getElementById('unitLabel').textContent    = unit;
        document.getElementById('totalPreview').textContent =
            'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function selectPayment(el, method) {
        document.querySelectorAll('.payment-label').forEach(l => {
            l.style.borderColor = '#e2e8f0';
            l.style.background  = '#fff';
            l.querySelector('.method-label').style.color = '#94a3b8';
        });
        const c = colorMap[method] || { border:'#3b82f6', bg:'#eff6ff' };
        el.style.borderColor = c.border;
        el.style.background  = c.bg;
        el.querySelector('.method-label').style.color = '#1e293b';
        el.querySelector('input[type=radio]').checked = true;
    }

    window.addEventListener('DOMContentLoaded', () => {
        const checkedInput = document.querySelector('.payment-label input:checked');
        if (checkedInput) {
            const method = checkedInput.value;
            selectPayment(checkedInput.closest('.payment-label'), method);
        }
        hitungTotal();
    });
</script>
@endpush
@endsection