@extends('layouts.admin')

@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Perbarui data partner yang sudah terdaftar.')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 font-medium mb-6">
        <a href="{{ route('admin.partners.index') }}" class="hover:text-indigo-600 transition">Partner</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-600">Edit — {{ $partner->name }}</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="px-8 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-700">Form Edit Partner</h3>
            <p class="text-xs text-slate-400 mt-0.5">Ubah data partner lalu klik Simpan Perubahan.</p>
        </div>

        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" class="px-8 py-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Validasi Error --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Nama Partner --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Partner <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $partner->name) }}"
                       placeholder="Contoh: PT. Maju Bersama"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('name') border-red-400 bg-red-50 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo URL --}}
            <div>
                <label for="logo_url" class="block text-sm font-semibold text-slate-700 mb-2">
                    Logo URL <span class="text-red-500">*</span>
                </label>

                <select id="logo_preset"
                        onchange="applyPreset(this.value)"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition mb-2 bg-white">
                    <option value="">— Pilih preset logo cepat —</option>
                    <option value="https://placehold.co/200x200/6366f1/white?text=Partner">Indigo</option>
                    <option value="https://placehold.co/200x200/3b82f6/white?text=Partner">Biru</option>
                    <option value="https://placehold.co/200x200/10b981/white?text=Partner">Hijau</option>
                    <option value="https://placehold.co/200x200/f59e0b/white?text=Partner">Kuning</option>
                    <option value="https://placehold.co/200x200/ef4444/white?text=Partner">Merah</option>
                    <option value="custom">— Masukkan URL sendiri —</option>
                </select>

                <input type="url"
                       id="logo_url"
                       name="logo_url"
                       value="{{ old('logo_url', $partner->logo_url) }}"
                       placeholder="https://placehold.co/200x200"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('logo_url') border-red-400 bg-red-50 @enderror"
                       required>
                @error('logo_url')
                    <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>
{{-- Email --}}
<div>
    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
        Email <span class="text-red-500">*</span>
    </label>
    <input type="email"
           id="email"
           name="email"
           value="{{ old('email', $partner->email) }}"
           placeholder="partner@email.com"
           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('email') border-red-400 bg-red-50 @enderror"
           required>
    @error('email')
        <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
    @enderror
</div>

</div>
            {{-- Preview Logo --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Preview Logo</label>
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <img id="logo_preview"
                         src="{{ old('logo_url', $partner->logo_url) }}"
                         alt="Preview"
                         class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">{{ $partner->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Preview akan berubah saat URL diperbarui.</p>
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.partners.index') }}"
                   class="text-sm font-semibold text-slate-500 hover:text-slate-700 px-4 py-3 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    const logoInput   = document.getElementById('logo_url');
    const logoPreview = document.getElementById('logo_preview');

    logoInput.addEventListener('input', function () {
        logoPreview.src = this.value.trim() || 'https://placehold.co/200x200/e2e8f0/94a3b8?text=Preview';
    });

    function applyPreset(val) {
        if (val && val !== 'custom') {
            logoInput.value = val;
            logoPreview.src = val;
        } else if (val === 'custom') {
            logoInput.value = '';
            logoInput.focus();
        }
    }
</script>

@endsection