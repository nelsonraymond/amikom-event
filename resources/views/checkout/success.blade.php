@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')
@php
    $isPaid = in_array(strtolower($transaction->status), ['success', 'settlement', 'used']);
@endphp

@if (!$isPaid)
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-amber-200 p-12 shadow-sm inline-block w-full max-w-md">
        <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-black mb-2">Pembayaran Belum Selesai</h2>
        <p class="text-slate-500 mb-6">
            Status pesanan <strong>{{ $transaction->order_id }}</strong> masih
            <strong>{{ $transaction->status }}</strong>. Selesaikan pembayaran terlebih dahulu.
        </p>
        <a href="{{ route('checkout.payment', $transaction->order_id) }}"
           class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Lanjutkan Pembayaran
        </a>
    </div>
</main>
@else
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
   <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Tunjukkan kode ini saat Check-in</p>
    <div id="qrcode" class="bg-white p-4 rounded-xl shadow-inner"></div>
    <p class="mt-4 font-mono font-bold text-slate-800">{{ strtoupper($transaction->order_id) }}</p>
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
        <h2 class="text-3xl font-black mb-4">Terima Kasih!</h2>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> telah berhasil.
            E-Ticket sudah dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>).
        </p>
        <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endif
@endsection