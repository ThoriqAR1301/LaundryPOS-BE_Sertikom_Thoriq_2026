@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')
@section('page-subtitle', 'Perbarui Data Pelanggan')

@section('content')
<div class="fade-in pt-2 space-y-5">

    <div class="rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#2563eb);border:1px solid #3b82f6">
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full opacity-10" style="background:#fff"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10" style="background:#93c5fd"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background:rgba(255,255,255,0.15)">
            <i class="fas fa-user-edit text-white text-xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider mb-0.5">Master Data</p>
            <p class="text-white font-bold text-lg">Edit Pelanggan</p>
            <p class="text-blue-300 text-xs mt-0.5">Memperbarui Data Milik <strong class="text-white">{{ $customer->user->name }}</strong></p>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#dbeafe">
                <i class="fas fa-user-edit" style="color:#2563eb"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Form Edit Pelanggan</h3>
                <p class="text-slate-400 text-xs mt-0.5">Ubah Field Yang Ingin Diperbarui</p>
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

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <label class="form-label">
                        <i class="fas fa-user text-violet-400 mr-1.5"></i> Nama Lengkap
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name', $customer->user->name) }}" placeholder="Nama Lengkap Pelanggan" class="form-input pl-10" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">
                        <i class="fas fa-envelope text-blue-400 mr-1.5"></i> Email
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email', $customer->user->email) }}" placeholder="email@contoh.com" class="form-input pl-10" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">
                        <i class="fas fa-lock text-cyan-400 mr-1.5"></i> Password Baru
                        <span class="text-slate-400 font-normal text-xs"> (Opsional)</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" id="passwordInput" placeholder="Kosongkan Jika Tidak Diubah" class="form-input pl-10 pr-12">
                        <button type="button" onclick="togglePass()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="passEye" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Isi Jika Ingin Mengganti Password Login Pelanggan
                    </p>
                </div>

                <div>
                    <label class="form-label">
                        <i class="fab fa-whatsapp text-emerald-400 mr-1.5"></i> No. WhatsApp
                    </label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="08xxxxxxxxxx" class="form-input pl-10" required>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Gunakan Nomor WhatsApp Aktif
                    </p>
                </div>

            </div>

            <div>
                <label class="form-label">
                    <i class="fas fa-map-marker-alt text-red-400 mr-1.5"></i> Alamat Lengkap
                </label>
                <div class="relative">
                    <i class="fas fa-map-marker-alt absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                    <textarea name="address" rows="3" placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota" class="form-input pl-10 resize-none" required>{{ old('address', $customer->address) }}</textarea>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle text-blue-300"></i>
                    Alamat Digunakan Untuk Pengiriman Cucian
                </p>
            </div>

            <div class="flex gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Perbarui Pelanggan
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
function togglePass() {
    const input = document.getElementById('passwordInput');
    const eye = document.getElementById('passEye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection