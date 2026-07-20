@extends('layouts.app')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-16">
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold">Tiketku</h1>
        <p class="text-slate-500 mt-2">Semua tiket yang pernah kamu pesan.</p>
    </div>

    @if ($transactions->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400 font-medium">Belum ada tiket. Yuk jelajahi event dulu!</p>
            <a href="{{ route('katalog') }}"
               class="inline-block mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                Jelajahi Event
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($transactions as $trx)
                @php
                    $isPaid = in_array(strtolower($trx->status), ['success', 'settlement', 'used']);
                @endphp
                <a href="{{ $isPaid ? route('ticket.show', $trx->order_id) : route('checkout.payment', $trx->order_id) }}"
                   class="block bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                    <div class="flex items-center gap-6">
                        <img src="{{ $trx->event->poster_path ? asset('storage/'.$trx->event->poster_path) : asset('assets/concert.png') }}"
                             alt="{{ $trx->event->title }}" class="w-20 h-20 rounded-2xl object-cover flex-shrink-0">

                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-lg truncate">{{ $trx->event->title }}</h3>
                            <p class="text-slate-500 text-sm">{{ $trx->event->date->translatedFormat('d M Y, H:i') }} • {{ $trx->event->location }}</p>
                            <p class="text-xs text-slate-400 font-mono mt-1">{{ $trx->order_id }}</p>
                        </div>

                        <div class="text-right flex-shrink-0">
                            @if ($isPaid)
                                <span class="inline-block px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full uppercase">Lunas</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full uppercase">{{ $trx->status }}</span>
                            @endif
                            <p class="text-indigo-600 font-bold mt-2">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</main>
@endsection