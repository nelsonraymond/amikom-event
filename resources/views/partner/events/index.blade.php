@extends('layouts.partner')
@section('title', 'Event Saya')
@section('page_title', 'Event Saya')
@section('page_subtitle', 'Kelola semua event yang kamu selenggarakan.')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('partner.events.create') }}"
       class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
        + Buat Event
    </a>
</div>

@if ($events->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
        <p class="text-slate-400 font-medium">Belum ada event.</p>
    </div>
@else
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                <tr>
                    <th class="text-left px-6 py-4">Event</th>
                    <th class="text-left px-6 py-4">Tanggal</th>
                    <th class="text-left px-6 py-4">Stok</th>
                    <th class="text-left px-6 py-4">Ulasan</th>
                    <th class="text-right px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($events as $event)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-bold">{{ $event->title }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $event->date->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $event->stock }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $event->reviews_count }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('partner.events.edit', $event) }}" class="text-indigo-600 font-bold hover:underline">Edit</a>
                            <form action="{{ route('partner.events.destroy', $event) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 font-bold hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>
@endif

@endsection