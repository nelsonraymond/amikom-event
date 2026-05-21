@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="max-w-7xl mx-auto px-6 pt-16 pb-8">
    <div class="text-center mb-10">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">
            Semua Event
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3 text-slate-900">
            Katalog <span class="text-indigo-600">AmikomEventHub</span>
        </h1>
        <p class="text-lg text-slate-500 max-w-xl mx-auto">
            Temukan semua event kampus — seminar, workshop, kompetisi, dan masih banyak lagi.
        </p>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap justify-center gap-3 mb-10">
        <a href="/katalog"
           class="px-5 py-2.5 rounded-xl font-bold text-sm transition
                  {{ !request('category') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            Semua Kategori
        </a>
        @foreach ($categories as $cat)
            <a href="/katalog?category={{ $cat->slug }}"
               class="px-5 py-2.5 rounded-xl font-bold text-sm transition
                      {{ request('category') === $cat->slug
                          ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200'
                          : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</section>

{{-- Grid Event --}}
<section class="max-w-7xl mx-auto px-6 pb-20">
    @if ($events->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($events as $event)
                <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="relative overflow-hidden aspect-[3/2]">
                        <img src="https://placehold.co/600x400/eef2ff/4f46e5?text={{ urlencode($event->title) }}"
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                            {{ $event->category->name ?? 'Umum' }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-2 group-hover:text-indigo-600 transition text-slate-800 line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            <span class="text-xl font-black text-indigo-600">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </span>
                            <a href="{{ url('event/' . $event->id) }}"
                               class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-sm hover:bg-indigo-600 hover:text-white transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-24 text-slate-400">
            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-lg font-semibold">Tidak ada event ditemukan</p>
            <p class="text-sm mt-1">
                @if (request('category'))
                    Coba pilih kategori lain atau <a href="/katalog" class="text-indigo-500 underline font-medium">lihat semua</a>.
                @else
                    Belum ada event yang tersedia saat ini.
                @endif
            </p>
        </div>
    @endif

    {{-- Navigasi --}}
    <div class="flex flex-wrap justify-center gap-3 mt-14">
        <a href="/" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">← Home</a>
        <a href="/profil" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Profil</a>
        <a href="/bantuan" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Bantuan</a>
        <a href="/contact" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Kontak</a>
    </div>
</section>

@endsection