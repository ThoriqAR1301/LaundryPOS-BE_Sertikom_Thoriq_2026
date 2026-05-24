@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Baru')
@section('page-subtitle', 'Input Data Transaksi Laundry')

@section('content')
<div class="fade-in pt-2 space-y-5">

    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background:linear-gradient(135deg,#0c4a6e,#0369a1,#0ea5e9);border:1px solid #38bdf8">
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#7dd3fc"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background:rgba(255,255,255,0.15)">
            <i class="fas fa-plus-circle text-white text-xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-sky-200 text-xs font-semibold uppercase tracking-wider mb-0.5">Transaksi</p>
            <p class="text-white font-bold text-lg">Buat Transaksi Baru</p>
            <p class="text-sky-300 text-xs mt-0.5">Isi Form Di Bawah Untuk Membuat Transaksi Laundry</p>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#e0f2fe">
                <i class="fas fa-file-invoice text-sky-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Transaksi Baru</h3>
                <p class="text-slate-400 text-xs mt-0.5">Isi Semua Data Transaksi Dengan Benar</p>
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

        <form action="{{ route('admin.transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="trxForm">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <label class="form-label">
                        <i class="fas fa-user text-blue-400 mr-1.5"></i> Pelanggan
                    </label>
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
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Pilih Pelanggan Yang Akan Melakukan Laundry
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
                            <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-unit="{{ $s->unit }}" {{ old('service_id')==$s->id ? 'selected' : '' }}>
                                {{ ucfirst($s->service_name) }} — Rp {{ number_format($s->price, 0, ',', '.') }}/{{ $s->unit }}
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Harga Akan Dihitung Otomatis
                    </p>
                </div>

            </div>


            <div>
                <label class="form-label">
                    <i class="fas fa-weight text-violet-400 mr-1.5"></i> Berat / Jumlah
                </label>
                <div class="relative">
                    <i class="fas fa-balance-scale absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}" placeholder="Contoh : 5" min="0.1" step="0.1" onkeyup="hitungTotal()" oninput="hitungTotal()" class="form-input pl-10 pr-16" required>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold" id="unitLabel">Unit</span>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Masukkan Berat (Kg) Atau Jumlah (Pcs) Sesuai Layanan
                </p>
            </div>

            <div class="rounded-xl p-4 border" style="background:linear-gradient(135deg,#eff6ff,#ecfdf5);border-color:#bfdbfe">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#3b82f6">
                            <i class="fas fa-calculator text-white text-xs"></i>
                        </div>
                        <span class="text-slate-600 text-sm font-medium">Estimasi Total Harga</span>
                    </div>
                    <span class="text-blue-600 font-bold text-xl" id="totalPreview">Rp 0</span>
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

            <div class="rounded-2xl border-2 border-dashed overflow-hidden transition-all duration-300" id="clothPhotoBox" style="border-color:#e2e8f0;background:#f8fafc">
                <div class="flex items-center justify-between px-5 py-4 border-b border-dashed" style="border-color:#e2e8f0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#fef3c7,#fde68a)">
                            <i class="fas fa-camera text-amber-500"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                Foto Kondisi Baju
                                <span class="text-xs font-normal px-2 py-0.5 rounded-full" style="background:#fef3c7;color:#d97706">Opsional</span>
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">Dokumentasi Baju Masuk Untuk Menghindari Komplain</p>
                        </div>
                    </div>

                    <div id="clothStatusBadge" class="hidden">
                        <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#d1fae5;color:#065f46">
                            <i class="fas fa-check-circle text-xs"></i> Foto Dipilih
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div id="clothDropZone" class="relative rounded-xl border-2 border-dashed transition-all duration-200 cursor-pointer group" style="border-color:#cbd5e1;background:#fff" onclick="document.getElementById('clothPhotoInput').click()" ondragover="event.preventDefault();this.style.borderColor='#3b82f6';this.style.background='#eff6ff'" ondragleave="this.style.borderColor='#cbd5e1';this.style.background='#fff'" ondrop="handleClothDrop(event)">
                        <div id="clothEmptyState" class="flex flex-col items-center justify-center py-8 px-4 text-center">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 transition-transform group-hover:scale-110" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe)">
                                <i class="fas fa-cloud-upload-alt text-2xl" style="color:#38bdf8"></i>
                            </div>
                            <p class="font-semibold text-slate-600 text-sm mb-1">Klik Atau Seret Foto Ke Sini</p>
                            <p class="text-xs text-slate-400">jpg / jpeg / png — Maks. 2MB</p>
                        </div>
                        <div id="clothPreviewWrap" class="hidden p-3">
                            <div class="relative inline-block">
                                <img id="clothPreview" src="#" alt="Preview" class="rounded-xl border border-slate-200 max-h-52 object-cover shadow-md w-full">
                                <div class="absolute bottom-0 left-0 right-0 px-3 py-2 rounded-b-xl" style="background:linear-gradient(to top,rgba(0,0,0,0.6),transparent)">
                                    <p class="text-white text-xs font-medium truncate" id="clothFileName"></p>
                                    <p class="text-white/70 text-xs" id="clothFileSize"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="file" name="cloth_photo" id="clothPhotoInput" accept="image/*" class="hidden" onchange="previewClothPhoto(this)">

                    <div class="flex items-center justify-between mt-3">
                        <p class="text-xs text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-blue-300"></i>
                            Foto Hanya Dilihat Oleh Admin Laundry
                        </p>
                        <button type="button" id="clearClothBtn" onclick="clearClothPhoto()" class="hidden items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-red-700 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50">
                            <i class="fas fa-trash-alt text-xs"></i> Hapus Foto
                        </button>
                    </div>
                
                </div>
            </div>

            <div class="flex gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Buat Transaksi
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
function hitungTotal() {
    const select = document.getElementById('serviceSelect');
    const opt = select.options[select.selectedIndex];
    const price = parseFloat(opt.dataset.price) || 0;
    const unit = opt.dataset.unit || 'Unit';
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const total = price * weight;
    document.getElementById('unitLabel').textContent = unit;
    document.getElementById('totalPreview').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
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
    el.querySelector('input[type=radio]').checked  = true;
}

function previewClothPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = e => {
        document.getElementById('clothPreview').src = e.target.result;
        document.getElementById('clothFileName').textContent = file.name;
        document.getElementById('clothFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('clothEmptyState').classList.add('hidden');
        document.getElementById('clothPreviewWrap').classList.remove('hidden');
        document.getElementById('clothStatusBadge').classList.remove('hidden');
        document.getElementById('clearClothBtn').classList.remove('hidden');
        document.getElementById('clearClothBtn').classList.add('flex');
        document.getElementById('clothDropZone').style.borderColor = '#10b981';
        document.getElementById('clothDropZone').style.background = '#f0fdf4';
    };
    reader.readAsDataURL(file);
}

function clearClothPhoto() {
    document.getElementById('clothPhotoInput').value = '';
    document.getElementById('clothPreview').src = '#';
    document.getElementById('clothEmptyState').classList.remove('hidden');
    document.getElementById('clothPreviewWrap').classList.add('hidden');
    document.getElementById('clothStatusBadge').classList.add('hidden');
    document.getElementById('clearClothBtn').classList.add('hidden');
    document.getElementById('clearClothBtn').classList.remove('flex');
    document.getElementById('clothDropZone').style.borderColor = '#cbd5e1';
    document.getElementById('clothDropZone').style.background = '#fff';
}

function handleClothDrop(e) {
    e.preventDefault();
    const input = document.getElementById('clothPhotoInput');
    const dt = new DataTransfer();
    dt.items.add(e.dataTransfer.files[0]);
    input.files = dt.files;
    previewClothPhoto(input);
    document.getElementById('clothDropZone').style.borderColor = '#cbd5e1';
    document.getElementById('clothDropZone').style.background = '#fff';
}

window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.payment-label input:checked');
    if (checked) selectPayment(checked.closest('.payment-label'));
    hitungTotal();
});
</script>
@endpush
@endsection