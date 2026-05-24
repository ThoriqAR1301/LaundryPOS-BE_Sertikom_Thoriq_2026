@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('page-title', 'Edit Layanan')
@section('page-subtitle', 'Perbarui Data ' . ucfirst($service->service_name))

@section('content')
<div class="fade-in pt-2 space-y-5">

    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background:linear-gradient(135deg,#78350f,#92400e,#b45309);border:1px solid #d97706">
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#fcd34d"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background:rgba(255,255,255,0.15)">
            <i class="fas fa-edit text-white text-xl"></i>
        </div>
        <div class="flex-1 relative z-10">
            <p class="text-amber-200 text-xs font-semibold uppercase tracking-wider mb-0.5">Sedang Mengedit</p>
            <p class="text-white font-bold text-lg capitalize">{{ $service->service_name }}</p>
            <p class="text-amber-300 text-xs mt-0.5">
                <i class="fas fa-tag mr-1"></i>
                Harga Saat Ini : Rp {{ number_format($service->price, 0, ',', '.') }} / {{ $service->unit }}
            </p>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fef3c7">
                <i class="fas fa-edit" style="color:#d97706"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Edit Layanan</h3>
                <p class="text-slate-400 text-xs mt-0.5">Ubah Data Yang Diperlukan Lalu Klik Simpan</p>
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

        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">
                    <i class="fas fa-concierge-bell text-blue-400 mr-1.5"></i> Jenis Layanan
                </label>
                <div class="relative">
                    <i class="fas fa-soap absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="service_name" class="form-input pl-10 pr-10 appearance-none cursor-pointer" required>
                        <option value="kiloan" {{ (old('service_name', $service->service_name)=='kiloan') ? 'selected' : '' }}>⚖️ Kiloan</option>
                        <option value="satuan" {{ (old('service_name', $service->service_name)=='satuan') ? 'selected' : '' }}>📦 Satuan</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Kiloan Dihitung Per Kg, Satuan Dihitung Per Pcs
                </p>
            </div>

            <div>
                <label class="form-label">
                    <i class="fas fa-money-bill-wave text-emerald-400 mr-1.5"></i> Harga (Per Satuan)
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 px-2 py-0.5 rounded-md text-xs font-bold" style="background:#d1fae5;color:#065f46">Rp</div>
                    <input type="number" name="price" value="{{ old('price', $service->price) }}" placeholder="0" min="0" step="100" class="form-input pl-14" required>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Harga Lama : <strong class="text-slate-500">Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                </p>
            </div>

            <div>
                <label class="form-label">
                    <i class="fas fa-ruler text-amber-400 mr-1.5"></i> Satuan
                </label>
                <div class="relative">
                    <i class="fas fa-balance-scale absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="unit" value="{{ old('unit', $service->unit) }}" placeholder="Contoh : Kg, Pcs" class="form-input pl-10" required>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Satuan Lama : <strong class="text-slate-500">{{ $service->unit }}</strong>
                </p>
            </div>

            <div class="rounded-xl p-4" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1.5px solid #fde68a">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#f59e0b">
                            <i class="fas fa-tag text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-amber-700">Preview Perubahan</p>
                            <p class="text-xs text-amber-600 mt-0.5" id="previewText">
                                Ubah Form Di Atas Untuk Melihat Preview
                            </p>
                        </div>
                    </div>
                    <p class="font-bold text-amber-700 text-sm" id="previewPrice">—</p>
                </div>
            </div>

            <div class="flex gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function updatePreview() {
        const service = document.querySelector('select[name="service_name"]');
        const price = document.querySelector('input[name="price"]');
        const unit = document.querySelector('input[name="unit"]');
        const text = document.getElementById('previewText');
        const prev = document.getElementById('previewPrice');

        const sName = service.options[service.selectedIndex]?.text?.replace(/^.+?\s/, '') || '—';
        const pVal = parseFloat(price.value) || 0;
        const uVal = unit.value || '—';

        if (service.value && pVal > 0 && unit.value) {
            text.textContent = sName + ' · Per ' + uVal;
            prev.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pVal) + ' / ' + uVal;
        } else {
            text.textContent = 'Ubah Form Di Atas Untuk Melihat Preview';
            prev.textContent = '—';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updatePreview();
        document.querySelector('select[name="service_name"]').addEventListener('change', updatePreview);
        document.querySelector('input[name="price"]').addEventListener('input', updatePreview);
        document.querySelector('input[name="unit"]').addEventListener('input', updatePreview);
    });
</script>
@endpush
@endsection