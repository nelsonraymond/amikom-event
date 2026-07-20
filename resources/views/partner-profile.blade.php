@extends('layouts.app')
@section('content')
<section class="max-w-5xl mx-auto px-6 py-16">
    <div class="flex items-center gap-6 mb-10">
        <div class="w-20 h-20 bg-indigo-100 rounded-3xl flex items-center justify-center text-indigo-600 font-bold text-2xl overflow-hidden">
            @if ($partner->logo_url)
                <img src="{{ $partner->logo_url }}" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($partner->name, 0, 2)) }}
            @endif
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900">{{ $partner->name }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xl font-bold text-amber-500">⭐ {{ $partner->average_rating ?: '-' }}</span>
                <span class="text-slate-400 text-sm">({{ $partner->reviews->count() }} ulasan · {{ $partner->events->count() }} event)</span>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Ulasan dari Peserta</h2>
    <div class="space-y-4">
        @forelse ($reviews as $review)
            <div class="p-6 bg-white rounded-3xl border border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="font-bold text-slate-800">{{ $review->user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $review->event->title }}</p>
                    </div>
                    <div class="flex gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                        @endfor
                    </div>
                </div>
                @if ($review->comment)
                    <p class="text-slate-500 text-sm">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="text-slate-400 text-sm">Belum ada ulasan untuk penyelenggara ini.</p>
        @endforelse
    </div>

    {{ $reviews->links() }}
</section>
@endsection