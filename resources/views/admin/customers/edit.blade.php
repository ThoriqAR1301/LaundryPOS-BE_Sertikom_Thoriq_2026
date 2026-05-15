@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')
@section('page-subtitle', 'Perbarui Data Pelanggan')

@section('content')
<div class="max-w-lg fade-in pt-2">
    <div class="card">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #3b82f6">
                <i class="fas fa-user-edit text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Edit Pelanggan</h3>
                <p class="text-slate-400 text-xs">Perbarui Data {{ $customer->user->name }}</p>
            </div>
        </div>
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="form-label"><i class="fas fa-user text-violet-400 mr-1"></i> Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="name" value="{{ $customer->user->name }}" class="form-input pl-10">
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-envelope text-blue-400 mr-1"></i> Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="email" name="email" value="{{ $customer->user->email }}" class="form-input pl-10">
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-phone text-emerald-400 mr-1"></i> No. HP</label>
                <div class="relative">
                    <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="phone" value="{{ $customer->phone }}" class="form-input pl-10">
                </div>
            </div>
            <div>
                <label class="form-label"><i class="fas fa-map-marker-alt text-red-400 mr-1"></i> Alamat</label>
                <div class="relative">
                    <i class="fas fa-map-marker-alt absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                    <textarea name="address" rows="3" class="form-input pl-10 resize-none">{{ $customer->address }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.customers.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection