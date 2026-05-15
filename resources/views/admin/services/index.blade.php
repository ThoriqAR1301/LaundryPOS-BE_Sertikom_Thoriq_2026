@extends('layouts.app')
@section('title', 'Layanan')
@section('page-title', 'Manajemen Layanan')
@section('page-subtitle', 'Kelola Daftar Layanan Laundry')

@section('content')
<div class="fade-in pt-2 space-y-5">


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #3b82f6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #3b82f6">
                    <i class="fas fa-tags text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #3b82f6">{{ $services->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Total Layanan</p>
            <p class="text-xs text-slate-400">Tersedia Di Sistem</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #10b981">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #10b981">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #10b981">{{ $services->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Layanan Aktif</p>
            <p class="text-xs text-slate-400">Siap Digunakan</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #f59e0b">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #f59e0b">
                    <i class="fas fa-star text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #f59e0b">{{ $services->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Layanan Unggulan</p>
            <p class="text-xs text-slate-400">Paling Diminati</p>
        </div>
    </div>

    
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Layanan</h3>
                <p class="text-slate-400 text-sm mt-0.5">Total {{ $services->count() }} Layanan</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Layanan
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="px-4 py-3 text-left rounded-l-xl">#</th>
                        <th class="px-4 py-3 text-left">Nama Layanan</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Satuan</th>
                        <th class="px-4 py-3 text-left rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($services as $i => $service)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color: #dbeafe">
                                    <i class="fas fa-soap text-xs" style="color: #3b82f6"></i>
                                </div>
                                <span class="font-semibold text-slate-700 capitalize">{{ $service->service_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="font-bold text-slate-800">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="badge" style="background-color: #cffafe; color: #0e7490">{{ $service->unit }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Yakin Hapus Layanan Ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-tags text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Belum Ada Layanan</p>
                                <a href="{{ route('admin.services.create') }}" class="btn-primary text-xs">
                                    <i class="fas fa-plus"></i> Tambah Layanan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection