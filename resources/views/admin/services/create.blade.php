@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')
@section('page-subtitle', 'Buat Layanan Laundry Baru')

@section('content')
<div class="max-w-lg fade-in pt-2">
    <div class="card">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #3b82f6">
                <i class="fas fa-plus text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Tambah Layanan</h3>
                <p class="text-slate-400 text-xs">Isi data layanan baru</p>
            </div>
        </div>
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="form-label"><i class="fas fa-concierge-bell text-blue-400 mr-1"></i> Jenis Layanan</label>
                <div class="relative">
                    <i class="fas fa-soap absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <select name="service_name" class="form-input pl-10 pr-10 appearance-none cursor-pointer">
                        <option value="">— Pilih Jenis Layanan —</option>
                        <option value="kiloan" {{ old('service_name')=='kiloan' ? 'selected' : '' }}>⚖️ Kiloan</option>
                        <option value="satuan" {{ old('service_name')=='satuan' ? 'selected' : '' }}>📦 Satuan</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-money-bill-wave text-emerald-400 mr-1"></i> Harga (Per Satuan)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">Rp</span>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="0" class="form-input pl-10">
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-ruler text-amber-400 mr-1"></i> Satuan</label>
                <div class="relative">
                    <i class="fas fa-balance-scale absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="unit" value="{{ old('unit') }}" placeholder="contoh: Kg, Pcs" class="form-input pl-10">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection