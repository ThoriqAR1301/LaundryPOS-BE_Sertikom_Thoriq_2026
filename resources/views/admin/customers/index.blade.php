@extends('layouts.app')
@section('title', 'Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Kelola Data Pelanggan Laundry')

@section('content')
<div class="fade-in pt-2 space-y-5">


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #7c3aed">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #7c3aed">
                    <i class="fas fa-users text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #7c3aed">{{ $customers->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Total Pelanggan</p>
            <p class="text-xs text-slate-400">Terdaftar Di Sistem</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #3b82f6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #3b82f6">
                    <i class="fas fa-user-check text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #3b82f6">{{ $customers->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Pelanggan Aktif</p>
            <p class="text-xs text-slate-400">Memiliki Transaksi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5" style="border-top: 4px solid #10b981">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: #10b981">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <span class="text-2xl font-bold" style="color: #10b981">+ {{ $customers->count() }}</span>
            </div>
            <p class="text-sm font-semibold text-slate-700">Total Terdaftar</p>
            <p class="text-xs text-slate-400">Semua Waktu</p>
        </div>
    </div>

    
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Pelanggan</h3>
                <p class="text-slate-400 text-sm mt-0.5">Total {{ $customers->count() }} Pelanggan</p>
            </div>
            <a href="{{ route('admin.customers.create') }}" class="btn-primary">
                <i class="fas fa-user-plus"></i> Tambah Pelanggan
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-head">
                        <th class="px-4 py-3 text-left rounded-l-xl">#</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-left">No. HP</th>
                        <th class="px-4 py-3 text-left">Alamat</th>
                        <th class="px-4 py-3 text-left rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($customers as $i => $customer)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #7c3aed, #a855f7)">
                                    <span class="text-white text-sm font-bold">{{ strtoupper(substr($customer->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700">{{ $customer->user->name }}</p>
                                    <p class="text-slate-400 text-xs">{{ $customer->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2 text-slate-600">
                                <i class="fas fa-phone text-slate-400 text-xs"></i>
                                {{ $customer->phone }}
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 max-w-xs truncate">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-slate-400 text-xs flex-shrink-0"></i>
                                <span class="truncate">{{ $customer->address }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Yakin Hapus Pelanggan Ini?')">
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
                                    <i class="fas fa-users text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Belum Ada Pelanggan</p>
                                <a href="{{ route('admin.customers.create') }}" class="btn-primary text-xs">
                                    <i class="fas fa-plus"></i> Tambah Pelanggan
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