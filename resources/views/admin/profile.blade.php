@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola Informasi Akun Anda')

@section('content')
<div class="fade-in pt-2">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <div class="card relative overflow-hidden flex flex-col items-center text-center">
            <div class="absolute inset-x-0 top-0 h-40 rounded-t-2xl" style="background:linear-gradient(135deg,#1d4ed8,#2563eb,#0ea5e9)"></div>
            <div class="absolute right-6 top-4 w-32 h-32 rounded-full opacity-10" style="background:#fff"></div>
            <div class="absolute left-4 top-10 w-20 h-20 rounded-full opacity-10" style="background:#38bdf8"></div>
            <div class="absolute right-24 top-24 w-12 h-12 rounded-full opacity-10" style="background:#bfdbfe"></div>

            <div class="relative pt-20 w-full flex flex-col items-center flex-1 justify-center pb-10 px-8">
                <div class="w-36 h-36 rounded-full flex items-center justify-center shadow-2xl ring-4 ring-white" style="background:linear-gradient(135deg,#1d4ed8,#06b6d4)">
                    <span class="text-white font-bold" style="font-size:4.5rem">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-slate-800 mt-6">{{ auth()->user()->name }}</h3>
                <p class="text-slate-400 text-base mt-2">{{ auth()->user()->email }}</p>

                <div class="mt-5">
                    <span class="px-6 py-2.5 rounded-full font-bold text-white inline-flex items-center gap-2" style="background:linear-gradient(to right,#3b82f6,#06b6d4);font-size:0.95rem">
                        <i class="fas fa-shield-alt"></i>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>

                <div class="w-full border-t border-slate-100 mt-10 pt-7">
                    <p class="text-sm text-slate-400 flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-alt text-blue-400"></i>
                        Bergabung Sejak
                        <strong class="text-slate-600">
                            {{ auth()->user()->created_at->format('d M Y') }}
                        </strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="card flex flex-col">
            <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eff6ff">
                    <i class="fas fa-user-circle text-blue-500"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Informasi Akun</h3>
                    <p class="text-xs text-slate-400">Data Diri Yang Terdaftar Di Sistem</p>
                </div>

            </div>

            <div class="flex-1 grid grid-cols-1 gap-8 content-start">

                <div class="flex items-center gap-4 p-4 rounded-xl transition-all hover:shadow-sm"
                     style="background:#f8fafc;border:1.5px solid #f1f5f9">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:#dbeafe">
                        <i class="fas fa-user" style="color:#3b82f6"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">Nama Lengkap</p>
                        <p class="font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl transition-all hover:shadow-sm"
                     style="background:#f8fafc;border:1.5px solid #f1f5f9">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:#cffafe">
                        <i class="fas fa-envelope" style="color:#06b6d4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">Alamat Email</p>
                        <p class="font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl transition-all hover:shadow-sm"
                     style="background:#f8fafc;border:1.5px solid #f1f5f9">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:#ede9fe">
                        <i class="fas fa-shield-alt" style="color:#7c3aed"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">Role / Jabatan</p>
                        <p class="font-bold text-slate-900 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl transition-all hover:shadow-sm"
                     style="background:#f8fafc;border:1.5px solid #f1f5f9">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:#d1fae5">
                        <i class="fas fa-calendar-check" style="color:#10b981"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 mb-0.5">Bergabung Sejak</p>
                        <p class="font-bold text-slate-900">{{ auth()->user()->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection