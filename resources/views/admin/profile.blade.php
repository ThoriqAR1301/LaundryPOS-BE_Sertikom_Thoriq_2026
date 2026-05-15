@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola Informasi Akun Anda')

@section('content')
<div class="max-w-lg fade-in pt-2 space-y-5">

    {{-- Avatar Card --}}
    <div class="card text-center">
        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl" style="background: linear-gradient(135deg, #3b82f6, #06b6d4)">
            <span class="text-white text-4xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        </div>
        <h3 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h3>
        <p class="text-slate-400 text-sm mt-1">{{ auth()->user()->email }}</p>
        <span class="badge mt-2 text-white" style="background-color: #3b82f6">
            <i class="fas fa-shield-alt text-xs"></i> {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>

    {{-- Info Card --}}
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-5">Informasi Akun</h3>
        <div class="space-y-3 text-sm">
            <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #dbeafe">
                    <i class="fas fa-user" style="color: #3b82f6"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs">Nama</p>
                    <p class="font-semibold text-slate-700">{{ auth()->user()->name }}</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
            </div>
            <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #cffafe">
                    <i class="fas fa-envelope" style="color: #06b6d4"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs">Email</p>
                    <p class="font-semibold text-slate-700">{{ auth()->user()->email }}</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
            </div>
            <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #ede9fe">
                    <i class="fas fa-shield-alt" style="color: #7c3aed"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs">Role</p>
                    <p class="font-semibold text-slate-700 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
            </div>
            <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #d1fae5">
                    <i class="fas fa-calendar" style="color: #10b981"></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-400 text-xs">Bergabung Sejak</p>
                    <p class="font-semibold text-slate-700">{{ auth()->user()->created_at->format('d M Y') }}</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-6">
            @csrf
            <button type="submit" class="w-full btn-danger justify-center">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>
@endsection