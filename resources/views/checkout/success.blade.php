@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
   <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Tunjukkan kode ini saat Check-in</p>
    <div id="qrcode" class="bg-white p-4 rounded-xl shadow-inner"></div>
    <p class="mt-4 font-mono font-bold text-slate-800">{{ strtoupper($transaction->order_id) }}</p>
</div>

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
            Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil. 
            E-Ticket akan dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
        </p>
        <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection