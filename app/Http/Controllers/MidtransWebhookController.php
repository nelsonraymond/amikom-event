<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Midtrans Webhook Received: ', $request->all());
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if (in_array($transaction->status, ['settlement', 'success', 'expired', 'failed'])) {
            return response()->json(['message' => 'Already processed']);
        }

        $checkoutController = new CheckoutController();

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
                $transaction->save();
            } elseif ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $transaction->save();
                $this->sendTicketEmail($transaction);
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->status = 'settlement';
            $transaction->save();
            $this->sendTicketEmail($transaction);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            // Pembayaran batal/gagal/kadaluarsa -> lepas reservasi stok sekarang juga,
            // jangan tunggu scheduler (biar stok cepat balik ke pembeli lain)
            $checkoutController->releaseReservation($transaction, 'failed');
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
            $transaction->save();
        }

        return response()->json(['message' => 'OK']);
    }

    private function sendTicketEmail(Transaction $transaction)
    {
        try {
            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                ->send(new \App\Mail\EventTicketMail($transaction));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
        }
    }
}