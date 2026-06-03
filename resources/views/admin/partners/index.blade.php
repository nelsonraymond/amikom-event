@extends('layouts.admin')

@section('title', 'Manajemen Partner')
@section('page_title', 'Manajemen Partner')
@section('page_subtitle', 'Kelola daftar partner dan sponsor AmikomEventHub.')

@section('content')

    {{-- Top bar --}}
    <div class="flex items-center justify-between mb-8">
        <span class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $partners->total() }} Partner
        </span>
        <a href="{{ route('admin.partners.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition shadow-md shadow-indigo-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Partner
        </a>
    </div>

    {{-- Search bar --}}
    <form action="{{ route('admin.partners.index') }}" method="GET" class="mb-6">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama partner..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    autocomplete="off"
                >
            </div>
            <button type="submit"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition">
                Cari
            </button>
            @if ($search)
                <a href="{{ route('admin.partners.index') }}"
                   class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 w-12">#</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 w-20">Logo</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Partner</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">URL Logo</th>
                    <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($partners as $index => $partner)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-medium">
                            {{ $partners->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center overflow-hidden p-1">
                                <img
                                    src="{{ $partner->logo_url }}"
                                    alt="{{ $partner->name }}"
                                    class="w-full h-full object-contain"
                                    onerror="this.style.display='none'; this.parentElement.innerHTML += '<svg class=\'w-6 h-6 text-slate-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5\'/></svg>'"
                                >
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">{{ $partner->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ $partner->logo_url }}" target="_blank"
                               class="text-indigo-500 hover:text-indigo-700 text-xs font-medium underline-offset-2 hover:underline block truncate max-w-xs"
                               title="{{ $partner->logo_url }}">
                                {{ $partner->logo_url }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.partners.edit', $partner) }}"
                                   class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                                      onsubmit="return confirm('Hapus partner \'{{ $partner->name }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="font-semibold text-sm">
                                @if ($search)
                                    Tidak ada partner yang cocok dengan "<strong>{{ $search }}</strong>"
                                @else
                                    Belum ada data partner.
                                @endif
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($partners->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-400 font-medium">
                    Menampilkan {{ $partners->firstItem() }}–{{ $partners->lastItem() }}
                    dari {{ $partners->total() }} partner
                </p>
                <div class="text-sm">
                    {{ $partners->links() }}
                </div>
            </div>
        @endif
    </div>

@endsection