@extends('layouts.app')

@section('content')

@php
    $isPaid = in_array(strtolower($transaction->status), ['success', 'settlement', 'used']);
@endphp

<div class="bg-indigo-600 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">

        @if (!$isPaid)
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-amber-400/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-black">Pembayaran Belum Selesai</h1>
                <p class="text-indigo-100 mt-2">Status pesanan ini masih <strong>{{ $transaction->status }}</strong>.</p>
            </div>

            <div class="bg-white text-slate-900 rounded-[2.5rem] p-8 shadow-2xl text-center">
                <p class="text-slate-500 mb-6">
                    Selesaikan pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> agar E-Ticket dan QR check-in dapat diterbitkan.
                </p>
                <a href="{{ route('checkout.payment', $transaction->order_id) }}"
                   class="block w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Lanjutkan Pembayaran
                </a>
                <a href="{{ route('ticket') }}"
                   class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600">Kembali ke Tiketku</a>
            </div>

        @else
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
                <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
            </div>

            <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
                <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
                    <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                    <h2 class="text-2xl font-black leading-tight">{{ $transaction->event->title }}</h2>

                    <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                    <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                </div>

                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                            <p class="font-bold text-lg">{{ $transaction->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                            <p class="font-bold text-lg">{{ $transaction->event->date->translatedFormat('d M, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                            <p class="font-bold">{{ $transaction->order_id }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                            <p class="font-bold">{{ $transaction->event->location }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                        <p class="text-slate-400 text-xs font-bold uppercase mb-4">Tunjukkan kode ini saat Check-in</p>
                        <div id="qrcode" class="bg-white p-4 rounded-xl shadow-inner"></div>
                        <p class="mt-4 font-mono font-bold text-slate-800">{{ strtoupper($transaction->order_id) }}</p>
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <button onclick="window.print()"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                        Cetak / Simpan PDF
                    </button>
                    <a href="{{ route('ticket') }}"
                        class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600">Kembali ke Tiketku</a>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (typeof window.QRCode === 'undefined') {
                        document.getElementById('qrcode').innerHTML = '<p class="text-red-500 text-xs">Gagal memuat QR</p>';
                        console.error('QRCode belum ke-load dari app.js');
                        return;
                    }
                    window.QRCode.toCanvas(
                        document.createElement('canvas'),
                        @json($transaction->order_id),
                        { width: 192, margin: 1 },
                        function (error, canvas) {
                            if (error) { console.error(error); return; }
                            document.getElementById('qrcode').appendChild(canvas);
                        }
                    );
                });
            </script>
        @endif

    </div>

</div>

@if ($isPaid)
@php
    $canReview = $transaction->user_id === auth()->id()
        && in_array($transaction->status, ['success', 'settlement'])
        && $transaction->event->date->copy()->addDay()->isPast()
        && !$transaction->review;
@endphp

@if ($canReview)
    <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-md mx-auto">
        <h3 class="font-bold text-lg mb-4">Beri Ulasan untuk Event Ini</h3>

        @if (session('success'))
            <p class="text-green-600 text-sm mb-3">{{ session('success') }}</p>
        @endif

        <form action="{{ route('reviews.store', $transaction) }}" method="POST">
            @csrf
            <div class="flex gap-1 mb-4" id="starRating">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" data-value="{{ $i }}" class="star-btn text-3xl text-slate-300 hover:scale-110 transition-transform">★</button>
                @endfor
                <input type="hidden" name="rating" id="ratingInput" required>
            </div>
            <textarea name="comment" rows="4" placeholder="Ceritakan pengalamanmu di event ini..." class="w-full border rounded-xl p-3 mb-4"></textarea>
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold">Kirim Ulasan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.star-btn');
            const input = document.getElementById('ratingInput');
            stars.forEach(function (star) {
                star.addEventListener('click', function () {
                    const value = parseInt(this.dataset.value);
                    input.value = value;
                    stars.forEach(function (s) {
                        const active = parseInt(s.dataset.value) <= value;
                        s.classList.toggle('text-amber-400', active);
                        s.classList.toggle('text-slate-300', !active);
                    });
                });
            });
        });
    </script>
@elseif ($transaction->review)
    <div class="mt-8 p-6 bg-slate-50 rounded-3xl max-w-md mx-auto">
        <p class="text-sm text-slate-500 mb-2">Ulasanmu:</p>
        <div class="flex gap-1 mb-2">
            @for ($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= $transaction->review->rating ? 'text-amber-400' : 'text-slate-300' }}">★</span>
            @endfor
        </div>
        @if ($transaction->review->comment)
            <p class="text-slate-700">{{ $transaction->review->comment }}</p>
        @endif
    </div>
@endif
@endif

@endsection