@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')
@section('page_subtitle')
 Ubah nama kategori {{ $category->name }}.
@endsection

@section('content')

    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        autofocus
                        class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-200' }}
                               rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Slug --}}
                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Preview Slug</label>
                    <div class="flex items-center gap-0 bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                        <span class="px-4 py-3 text-sm text-slate-400 font-mono border-r border-slate-200 bg-slate-100 shrink-0">
                            /kategori/
                        </span>
                        <input type="text" id="slug-preview" readonly
                               value="{{ $category->slug }}"
                               class="flex-1 px-4 py-3 bg-slate-50 text-sm text-slate-500 font-mono focus:outline-none">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm transition shadow-md shadow-amber-100">
                        Perbarui Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="flex-1 py-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const nameInput = document.getElementById('name');
        const slugPreview = document.getElementById('slug-preview');
        const toSlug = str => str.toLowerCase().trim()
            .replace(/[\s_]+/g, '-')
            .replace(/[^a-z0-9-]/g, '')
            .replace(/-+/g, '-');
        nameInput.addEventListener('input', () => slugPreview.value = toSlug(nameInput.value));
    </script>

@endsection