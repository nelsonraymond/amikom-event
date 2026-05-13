@extends('layouts.admin')

@section('page_title', 'Daftar Partner')
@section('page_subtitle', 'Kelola semua data partner yang terdaftar.')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <h2 class="text-lg font-bold text-slate-700">Daftar Partner</h2>
        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
            {{ $partners->count() }} Partner
        </span>
    </div>
    <a href="{{ route('admin.partners.create') }}"
       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Partner
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700">Data Partner</h3>
        <span class="text-xs text-slate-400 font-medium">{{ $partners->count() }} Partner</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold w-12">No</th>
                    <th class="px-6 py-4 text-left font-semibold w-24">Logo</th>
                    <th class="px-6 py-4 text-left font-semibold">Nama Partner</th>
                    <th class="px-6 py-4 text-left font-semibold w-36">Dibuat</th>
                    <th class="px-6 py-4 text-center font-semibold w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-4">
                        <span class="w-7 h-7 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-xs">
                            {{ $index + 1 }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <img src="{{ $partner->logo_url }}"
                             alt="Logo {{ $partner->name }}"
                             class="w-12 h-12 rounded-xl object-cover border border-slate-100 shadow-sm">
                    </td>

                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-800">{{ $partner->name }}</span>
                    </td>


                    <td class="px-6 py-4">
                        <span class="text-slate-400 text-xs font-medium">
                            {{ $partner->created_at->format('d M Y') }}
                        </span>
                    </td>

                    {{-- Tombol Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">

                            {{-- Edit --}}
                            <a href="{{ route('admin.partners.edit', $partner) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-100 transition"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.partners.destroy', $partner) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus partner {{ $partner->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="font-semibold text-slate-500">Belum ada data partner</p>
                            <a href="{{ route('admin.partners.create') }}"
                               class="text-indigo-500 hover:underline text-sm font-medium">
                                + Tambah partner pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection