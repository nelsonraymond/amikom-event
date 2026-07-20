<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class EventController extends Controller
{
    public function show(\App\Models\Event $event)
{
   // Mengambil daftar kategori untuk keperluan menu footer
    $categories = \App\Models\Category::all();
    $event->load(['category', 'partner', 'reviews.user']);
    // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
    return view('event-detail', compact('categories', 'event'));
}
    public function checkout(){
        return view('checkout');
    }

    public function partnerProfile(Partner $partner)
{
    $partner->load('events');

    $reviews = Review::whereIn('event_id', $partner->events->pluck('id'))
        ->with(['user', 'event'])
        ->latest()
        ->paginate(10);

    return view('partner-profile', compact('partner', 'reviews'));
}
}
