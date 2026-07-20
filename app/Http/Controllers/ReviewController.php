<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        // 1. Transaksi harus milik user yang login
        abort_if($transaction->user_id !== Auth::id(), 403, 'Transaksi ini bukan milik Anda.');

        // 2. Status harus lunas
        abort_unless(
    in_array($transaction->status, ['success', 'settlement', 'used']),
    403,
    'Transaksi belum lunas.'
);

        // 3. Event sudah selesai minimal 1 hari (pakai copy() biar Carbon-nya gak ke-mutate)
        $event = $transaction->event;
        abort_unless(
            $event->date->copy()->addDay()->isPast(),
            403,
            'Ulasan baru bisa diberikan sehari setelah acara selesai.'
        );

        // 4. Belum pernah direview
        if ($transaction->review()->exists()) {
            return back()->with('error', 'Transaksi ini sudah pernah diulas.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id'        => Auth::id(),
            'event_id'       => $event->id,
            'rating'         => $validated['rating'],
            'comment'        => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih atas ulasannya!');
    }
}