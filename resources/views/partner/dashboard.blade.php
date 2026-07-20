@extends('layouts.partner')
@section('title', 'Dashboard')
@section('page_title', 'Selamat datang, ' . $partner->name)
@section('page_subtitle', 'Ringkasan performa event yang kamu selenggarakan.')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Pendapatan</p>
        <p class="text-3xl font-black text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <p class="text-slate-400 text-xs font-bold uppercase mb-2">Tiket Terjual</p>
        <p class="text-3xl font-black text-slate-900">{{ $totalTicketsSold }}</p>
    </div>
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <p class="text-slate-400 text-xs font-bold uppercase mb-2">Rating Rata-rata</p>
        <p class="text-3xl font-black text-amber-500">⭐ {{ $partner->average_rating ?: '-' }}</p>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm mb-10">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900">Pertumbuhan Penjualan Tiket</h2>
            <p class="text-slate-400 text-sm">30 hari terakhir</p>
        </div>
    </div>

    <canvas id="salesChart" height="90"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.Chart === 'undefined') {
        document.getElementById('salesChart').outerHTML =
            '<p class="text-red-500 text-sm">Gagal memuat grafik.</p>';
        console.error('Chart.js belum ke-load dari app.js');
        return;
    }

    new window.Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Tiket Terjual',
                data: @json($chartData),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.08)',
                fill: true,
                tension: 0.35,
                pointRadius: 2,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 } // jangan tampilkan angka desimal
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-extrabold">Event Kamu</h2>
    <a href="{{ route('partner.events.create') }}"
       class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
        + Buat Event
    </a>
</div>

@if ($events->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
        <p class="text-slate-400 font-medium mb-4">Kamu belum punya event. Yuk buat yang pertama!</p>
        <a href="{{ route('partner.events.create') }}"
           class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Buat Event
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($events as $event)
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-extrabold text-lg">{{ $event->title }}</h3>
                    <a href="{{ route('partner.events.edit', $event) }}" class="text-indigo-600 text-sm font-bold hover:underline">Edit</a>
                </div>
                <p class="text-slate-500 text-sm mb-3">{{ $event->date->translatedFormat('d M Y, H:i') }} • {{ $event->location }}</p>
                <div class="flex items-center gap-4 text-sm">
                    <span class="text-slate-400">Stok: <span class="font-bold text-slate-700">{{ $event->stock }}</span></span>
                    <span class="text-slate-400">Ulasan: <span class="font-bold text-slate-700">{{ $event->reviews_count }}</span></span>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection