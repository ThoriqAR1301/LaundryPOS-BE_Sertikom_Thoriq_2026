@extends('layouts.app')
@section('title', 'Layanan')
@section('page-title', 'Manajemen Layanan')
@section('page-subtitle', 'Kelola Daftar Layanan Laundry')

@section('content')
<div class="fade-in pt-2 space-y-5">


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden" style="border-top:4px solid #3b82f6">
            <div class="absolute -right-3 -top-3 w-16 h-16 rounded-full opacity-5" style="background:#3b82f6"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#3b82f6">
                    <i class="fas fa-tags text-white text-sm"></i>
                </div>
                <span class="text-2xl font-bold" style="color:#3b82f6">{{ $services->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Total Layanan</p>
            <p class="text-xs text-slate-400">Tersedia Di Sistem</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden" style="border-top:4px solid #10b981">
            <div class="absolute -right-3 -top-3 w-16 h-16 rounded-full opacity-5" style="background:#10b981"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#10b981">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <span class="text-2xl font-bold" style="color:#10b981">{{ $services->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Layanan Aktif</p>
            <p class="text-xs text-slate-400">Siap Digunakan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden" style="border-top:4px solid #f59e0b">
            <div class="absolute -right-3 -top-3 w-16 h-16 rounded-full opacity-5" style="background:#f59e0b"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#f59e0b">
                    <i class="fas fa-star text-white text-sm"></i>
                </div>
                <span class="text-2xl font-bold" style="color:#f59e0b">{{ $services->count() }}</span>
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
                        <th class="px-4 py-3 text-left rounded-l-xl">No</th>
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
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#dbeafe">
                                    <i class="fas fa-soap text-xs" style="color:#3b82f6"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 capitalize">{{ $service->service_name }}</p>
                                    <p class="text-xs text-slate-400">ID #{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>

                            </div>
                        </td>

                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-semibold px-1.5 py-0.5 rounded-md" style="background:#d1fae5;color:#065f46">Rp</span>
                                <span class="font-bold text-slate-800">
                                    {{ number_format($service->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-3.5">
                            <span class="badge" style="background:#cffafe;color:#0e7490">
                                <i class="fas fa-{{ strtolower($service->unit) == 'kg' ? 'weight' : 'box' }} text-xs"></i>
                                {{ $service->unit }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors" title="Edit Layanan">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" data-confirm-title="Hapus Layanan?" data-confirm-message="Layanan &quot;{{ ucfirst($service->service_name) }}&quot; Akan Dihapus Permanen. Transaksi Yang Menggunakan Layanan Ini Mungkin Terpengaruh" data-confirm-type="danger" data-confirm-ok="Hapus" data-no-loading>
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors" title="Hapus Layanan">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#f1f5f9">
                                    <i class="fas fa-tags text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium text-sm">Belum Ada Layanan</p>
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