<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        if (in_array(strtolower($transaction->status), ['success', 'settlement', 'used'])) {
    return redirect()->route('checkout.success', $order_id);
}

        // Kalau reservasi sudah lewat waktu, jangan biarkan customer bayar tiket yang stoknya sudah dilepas
        if ($transaction->status === 'pending' && $transaction->expires_at && $transaction->expires_at->isPast()) {
            return redirect()->route('checkout.create', $transaction->event_id)
                ->with('error', 'Waktu pembayaran habis dan reservasi tiket sudah dilepas. Silakan pesan ulang.');
        }

        return view('checkout.payment', compact('transaction', 'categories'));
    }

   public function store(Request $request, Event $event)
{
    $request->validate([
        'customer_name'  => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
    ]);

    $orderId = 'TRX-' . time() . '-' . Str::random(5);

    // Event gratis -> tidak ada biaya admin. Event berbayar -> + biaya admin 5000.
    $isFreeEvent = (int) $event->price === 0;
    $totalPrice  = $isFreeEvent ? 0 : $event->price + 5000;

    // --- RESERVASI STOK ATOMIK ---
    try {
        $transaction = DB::transaction(function () use ($request, $event, $orderId, $totalPrice, $isFreeEvent) {
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            if (!$lockedEvent || $lockedEvent->stock <= 0) {
                throw new \RuntimeException('STOCK_EMPTY');
            }

            $lockedEvent->decrement('stock');

            return Transaction::create([
                'user_id'         => auth()->id(),
                'event_id'        => $event->id,
                'order_id'        => $orderId,
                'customer_name'   => $request->customer_name,
                'customer_email'  => $request->customer_email,
                'customer_phone'  => $request->customer_phone,
                'total_price'     => $totalPrice,
                // Event gratis langsung "success", tidak pernah nyangkut di 'pending'
                'status'          => $isFreeEvent ? 'success' : 'pending',
                'expires_at'      => $isFreeEvent ? null : now()->addSeconds(30),
            ]);
        });
    } catch (\RuntimeException $e) {
        return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
    }

    // --- BYPASS MIDTRANS UNTUK EVENT GRATIS ---
    if ($isFreeEvent) {
        try {
            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                ->send(new \App\Mail\EventTicketMail($transaction));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email E-Ticket (event gratis): ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $transaction->order_id);
    }

    // --- INTEGRASI SNAP MIDTRANS (event berbayar) ---
    \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized  = true;
    \Midtrans\Config::$is3ds        = true;

    $params = [
        'transaction_details' => [
            'order_id'     => $orderId,
            'gross_amount' => $totalPrice,
        ],
        'customer_details' => [
            'first_name' => $request->customer_name,
            'email'      => $request->customer_email,
            'phone'      => $request->customer_phone,
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $transaction->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.payment', $transaction->order_id);
    } catch (\Exception $e) {
        $this->releaseReservation($transaction, 'failed');

        return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
    }
}

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        try {
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                if (in_array($trx_status, ['settlement', 'capture']) && strtolower($transaction->status) === 'pending') {
                    // Stok TIDAK dikurangi lagi di sini -- sudah direservasi saat checkout.
                    // Cukup ubah status dan kirim tiket.
                    $transaction->update(['status' => 'success']);

                    try {
                        \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                            ->send(new \App\Mail\EventTicketMail($transaction));
                    } catch (\Exception $e) {
                        Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    /**
     * Lepas reservasi stok (dipakai saat gagal generate Snap Token,
     * atau dipanggil dari command scheduler untuk transaksi expired).
     */
    public function releaseReservation(Transaction $transaction, string $newStatus = 'expired'): void
    {
        DB::transaction(function () use ($transaction, $newStatus) {
            // Kunci baris transaksi biar tidak dilepas dua kali oleh proses bersamaan
            $locked = Transaction::where('id', $transaction->id)->lockForUpdate()->first();

            if (!$locked || $locked->status !== 'pending') {
                return; // sudah diproses proses lain / sudah bukan pending
            }

            $locked->update(['status' => $newStatus]);

            if ($locked->event_id) {
                Event::where('id', $locked->event_id)->increment('stock');
            }
        });
    }
}