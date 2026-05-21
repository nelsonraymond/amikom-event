@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="max-w-3xl mx-auto px-6 py-16 text-center">
    <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">
        FAQ & Bantuan
    </span>
    <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-slate-900">
        Ada yang bisa <span class="text-indigo-600">kami bantu?</span>
    </h1>
    <p class="text-lg text-slate-500 max-w-lg mx-auto">
        Jawaban atas pertanyaan paling sering ditanyakan seputar AmikomEventHub.
    </p>
</section>

{{-- FAQ --}}
<section class="max-w-3xl mx-auto px-6 pb-20 space-y-4">

    @php
    $faqs = [
        [
            'q' => 'Apa itu AmikomEventHub?',
            'a' => 'AmikomEventHub adalah platform reservasi tiket event online untuk mahasiswa dan penyelenggara acara di lingkungan Universitas AMIKOM Yogyakarta. Tersedia berbagai event seperti seminar, workshop, kompetisi, dan lainnya.',
            'icon' => '🎯',
            'color' => 'indigo',
        ],
        [
            'q' => 'Bagaimana cara memesan tiket event?',
            'a' => 'Pilih event di halaman utama atau katalog → klik "Lihat Detail" → klik "Pesan Sekarang" → lakukan pembayaran via Midtrans. E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.',
            'icon' => '🎟️',
            'color' => 'purple',
        ],
        [
            'q' => 'Metode pembayaran apa saja yang tersedia?',
            'a' => 'AmikomEventHub menggunakan Midtrans sebagai payment gateway. Tersedia: transfer bank (BCA, BNI, BRI, Mandiri), e-wallet (GoPay, OVO, Dana, ShopeePay), QRIS, serta Alfamart/Indomaret.',
            'icon' => '💳',
            'color' => 'green',
        ],
        [
            'q' => 'Di mana saya bisa melihat tiket yang sudah dibeli?',
            'a' => 'Buka halaman "Tiketku" di menu navigasi atas. Semua tiket yang sudah dibeli akan tampil di sana beserta QR Code untuk check-in di lokasi event.',
            'icon' => '🎫',
            'color' => 'amber',
        ],
        [
            'q' => 'Apakah tiket bisa direfund atau dibatalkan?',
            'a' => 'Tiket yang sudah dibeli tidak dapat direfund. Pastikan kamu memeriksa detail event (tanggal, lokasi, harga) sebelum melakukan pemesanan.',
            'icon' => '⚠️',
            'color' => 'red',
        ],
        [
            'q' => 'Bagaimana cara menggunakan filter kategori?',
            'a' => 'Di halaman utama atau katalog, tersedia tombol filter kategori di bagian atas daftar event. Klik salah satu kategori (Seminar, Workshop, dll.) untuk menampilkan event yang sesuai saja.',
            'icon' => '🔍',
            'color' => 'indigo',
        ],
        [
            'q' => 'Siapa yang bisa menjadi penyelenggara event?',
            'a' => 'Saat ini pengelolaan event dilakukan oleh Admin AmikomEventHub. Jika kamu tertarik mengadakan event, hubungi kami melalui halaman Kontak.',
            'icon' => '🏢',
            'color' => 'purple',
        ],
    ];

    $colorMap = [
        'indigo' => ['bg' => 'bg-indigo-50',  'icon' => 'bg-indigo-100', 'border' => 'border-indigo-100'],
        'purple' => ['bg' => 'bg-purple-50',  'icon' => 'bg-purple-100', 'border' => 'border-purple-100'],
        'green'  => ['bg' => 'bg-green-50',   'icon' => 'bg-green-100',  'border' => 'border-green-100'],
        'amber'  => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-100',  'border' => 'border-amber-100'],
        'red'    => ['bg' => 'bg-red-50',     'icon' => 'bg-red-100',    'border' => 'border-red-100'],
    ];
    @endphp

    @foreach ($faqs as $i => $faq)
        @php $c = $colorMap[$faq['color']]; @endphp
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="flex items-start gap-4 p-6">
                <div class="w-10 h-10 {{ $c['icon'] }} rounded-2xl flex items-center justify-center text-lg shrink-0 mt-0.5">
                    {{ $faq['icon'] }}
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-800 mb-2">{{ $faq['q'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
        </div>
    @endforeach

    {{-- CTA Kontak --}}
    <div class="mt-10 bg-indigo-600 rounded-3xl p-8 text-center text-white relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full pointer-events-none"></div>
        <div class="absolute -left-6 -top-6 w-24 h-24 bg-indigo-400 opacity-20 rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <p class="text-indigo-200 font-bold uppercase tracking-widest text-xs mb-2">Masih ada pertanyaan?</p>
            <h3 class="text-2xl font-extrabold mb-3">Hubungi Tim Kami</h3>
            <p class="text-indigo-100 text-sm mb-6 max-w-sm mx-auto">
                Tidak menemukan jawaban yang kamu cari? Tim kami siap membantu kamu.
            </p>
            <a href="/contact"
               class="inline-block px-8 py-3 bg-white text-indigo-600 rounded-2xl font-black hover:scale-105 transition-transform shadow-xl">
                Kontak Kami →
            </a>
        </div>
    </div>

    {{-- Navigasi --}}
    <div class="flex flex-wrap justify-center gap-3 mt-8">
        <a href="/" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">← Home</a>
        <a href="/katalog" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Katalog Event</a>
        <a href="/profil" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Profil</a>
        <a href="/contact" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">Kontak</a>
    </div>

</section>

@endsection