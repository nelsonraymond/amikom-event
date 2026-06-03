@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="max-w-5xl mx-auto px-6 py-20 text-center">
    <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">
        Tentang Platform
    </span>
    <h1 class="text-4xl md:text-6xl font-extrabold mb-5 text-slate-900 leading-tight">
        Apa itu <span class="text-indigo-600">AmikomEventHub?</span>
    </h1>
    <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">
        Platform reservasi tiket event online terpercaya untuk mahasiswa dan penyelenggara profesional
        di lingkungan Universitas AMIKOM Yogyakarta.
    </p>

    {{-- Tombol navigasi --}}
    <div class="flex flex-wrap justify-center gap-4 mt-8">
        <a href="/katalog"
           class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
            Jelajahi Event
        </a>
        <a href="/bantuan"
           class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-400 hover:text-indigo-600 transition">
            Pusat Bantuan
        </a>
    </div>
</section>

{{-- Statistik --}}
<section class="bg-indigo-600 py-14">
    <div class="max-w-5xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
        @foreach ([
            ['label' => 'Event Tersedia',  'value' => '50+',  'icon' => '🎪'],
            ['label' => 'Kategori Event',  'value' => '8+',   'icon' => '🏷️'],
            ['label' => 'Partner Aktif',   'value' => '20+',  'icon' => '🤝'],
            ['label' => 'Tiket Terjual',   'value' => '500+', 'icon' => '🎟️'],
        ] as $stat)
            <div>
                <div class="text-3xl mb-1">{{ $stat['icon'] }}</div>
                <div class="text-4xl font-black mb-1">{{ $stat['value'] }}</div>
                <div class="text-indigo-200 text-sm font-semibold">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- Visi Misi --}}
<section class="max-w-5xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 space-y-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl">🎯</div>
            <h2 class="text-2xl font-extrabold text-slate-800">Visi</h2>
            <p class="text-slate-500 leading-relaxed">
                Menjadi platform event kampus terdepan yang menghubungkan mahasiswa dengan berbagai kegiatan
                akademik, kreatif, dan profesional secara mudah, aman, dan terpercaya.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 space-y-4">
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-2xl">🚀</div>
            <h2 class="text-2xl font-extrabold text-slate-800">Misi</h2>
            <ul class="space-y-2 text-slate-500 text-sm leading-relaxed">
                @foreach ([
                    'Menyediakan akses event kampus dalam satu platform terintegrasi.',
                    'Memudahkan proses pemesanan tiket dengan sistem pembayaran online aman.',
                    'Mendukung penyelenggara event dalam mengelola dan mempromosikan acara.',
                    'Membangun ekosistem event kampus yang aktif dan inklusif.',
                ] as $misi)
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-indigo-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $misi }}
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</section>

{{-- Fitur Unggulan --}}
<section class="bg-slate-50 py-20">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Fitur Unggulan</h2>
            <p class="text-slate-500">Semua yang kamu butuhkan untuk menemukan dan memesan tiket event.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([
                ['icon' => '🔍', 'title' => 'Filter Kategori',    'desc' => 'Cari event berdasarkan kategori: seminar, workshop, kompetisi, dan lainnya.',         'color' => 'bg-indigo-50'],
                ['icon' => '💳', 'title' => 'Pembayaran Aman',    'desc' => 'Terintegrasi dengan Midtrans — mendukung transfer bank, e-wallet, dan QRIS.',          'color' => 'bg-green-50'],
                ['icon' => '🎟️', 'title' => 'E-Ticket Instan',   'desc' => 'Tiket dikirim otomatis setelah pembayaran. Scan QR Code langsung di pintu masuk.',     'color' => 'bg-amber-50'],
                ['icon' => '📅', 'title' => 'Info Event Lengkap', 'desc' => 'Detail tanggal, lokasi, harga, dan deskripsi event tersedia di satu halaman.',          'color' => 'bg-purple-50'],
                ['icon' => '🤝', 'title' => 'Partner Terpercaya', 'desc' => 'Didukung berbagai institusi dan perusahaan yang menjamin kualitas setiap event.',        'color' => 'bg-rose-50'],
                ['icon' => '⚡', 'title' => 'Cepat & Responsif',  'desc' => 'Antarmuka modern dan responsif, nyaman digunakan di perangkat apa pun.',                'color' => 'bg-sky-50'],
            ] as $fitur)
                <div class="group {{ $fitur['color'] }} rounded-3xl p-6 border border-white hover:shadow-lg transition-shadow duration-300">
                    <div class="text-3xl mb-4">{{ $fitur['icon'] }}</div>
                    <h3 class="font-extrabold text-slate-800 mb-2">{{ $fitur['title'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $fitur['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Teknologi --}}
<section class="max-w-5xl mx-auto px-6 py-20">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Dibangun dengan Teknologi Modern</h2>
        <p class="text-slate-500">Stack yang mendukung performa dan keandalan AmikomEventHub.</p>
    </div>
    <div class="flex flex-wrap justify-center gap-3">
        @foreach ([
            ['name' => 'Laravel 11',     'color' => 'bg-red-50 text-red-600 border-red-100'],
            ['name' => 'PHP 8.3',        'color' => 'bg-purple-50 text-purple-600 border-purple-100'],
            ['name' => 'Tailwind CSS',   'color' => 'bg-sky-50 text-sky-600 border-sky-100'],
            ['name' => 'MySQL',          'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
            ['name' => 'Eloquent ORM',   'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
            ['name' => 'Blade Engine',   'color' => 'bg-orange-50 text-orange-600 border-orange-100'],
            ['name' => 'Midtrans',       'color' => 'bg-green-50 text-green-600 border-green-100'],
            ['name' => 'Git & GitHub',   'color' => 'bg-slate-100 text-slate-700 border-slate-200'],
        ] as $tech)
            <span class="px-5 py-2.5 {{ $tech['color'] }} border rounded-2xl text-sm font-bold">
                {{ $tech['name'] }}
            </span>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="max-w-5xl mx-auto px-6 pb-20">
    <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-center text-white relative overflow-hidden">
        <div class="absolute -right-16 -bottom-16 w-56 h-56 bg-white opacity-10 rounded-full pointer-events-none"></div>
        <div class="absolute -left-8 -top-8 w-32 h-32 bg-indigo-400 opacity-20 rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold mb-3">Siap Menemukan Event Seru?</h2>
            <p class="text-indigo-100 mb-7 max-w-md mx-auto">
                Jelajahi ratusan event kampus dan pesan tiketmu sekarang — mudah, cepat, dan aman.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/katalog"
                   class="px-8 py-3.5 bg-white text-indigo-600 rounded-2xl font-black hover:scale-105 transition-transform shadow-xl">
                    Lihat Semua Event →
                </a>
                <a href="/contact"
                   class="px-8 py-3.5 border-2 border-white/40 text-white rounded-2xl font-bold hover:bg-white/10 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    {{-- Navigasi --}}
    <div class="flex flex-wrap justify-center gap-3 mt-10">
        <a href="/" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">← Home</a>
        <a href="/katalog" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Katalog Event</a>
        <a href="/bantuan" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Bantuan</a>
        <a href="/contact" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Kontak</a>
    </div>
</section>

@endsection