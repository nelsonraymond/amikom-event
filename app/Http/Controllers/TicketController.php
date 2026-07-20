<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;

class TicketController extends Controller
{
    /**
     * List semua tiket milik user yang sedang login.
     */
    public function index()
    {
        $categories = Category::all();

        $transactions = Transaction::with(['event', 'review'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('ticket.index', compact('transactions', 'categories'));
    }

    /**
     * Detail 1 tiket (kartu tiket + QR), hanya bisa diakses pemiliknya.
     */
    public function show(Transaction $transaction)
    {
        abort_unless($transaction->user_id === auth()->id(), 403);

        $transaction->load(['event', 'review']);

        $categories = Category::all();

        return view('ticket.show', compact('transaction', 'categories'));
    }
}