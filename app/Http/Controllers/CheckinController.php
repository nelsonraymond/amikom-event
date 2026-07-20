<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function adminScanner()
    {
        return view('checkin.scanner', ['verifyUrl' => route('admin.scanner.verify')]);
    }

    public function partnerScanner()
    {
        return view('checkin.scanner', ['verifyUrl' => route('partner.scanner.verify')]);
    }

    public function verifyAdmin(Request $request)
    {
        return $this->verify($request, scopeToPartner: false);
    }

    public function verifyPartner(Request $request)
    {
        return $this->verify($request, scopeToPartner: true);
    }

    private function verify(Request $request, bool $scopeToPartner)
    {
        $request->validate(['order_id' => 'required|string']);

        // Toleransi kalau yang ke-scan itu full URL/teks lain, ambil pola TRX-nya saja
        $orderId = trim($request->order_id);

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['valid' => false, 'message' => 'Tiket tidak ditemukan.'], 404);
        }

        if ($scopeToPartner) {
            $partnerId = Auth::guard('partner')->id();
            if (!$transaction->event || $transaction->event->partner_id !== $partnerId) {
                return response()->json(['valid' => false, 'message' => 'Tiket ini bukan untuk event Anda.'], 403);
            }
        }

        $status = strtolower($transaction->status);

        if ($status === 'used') {
            $when = $transaction->checked_in_at ? $transaction->checked_in_at->translatedFormat('d M Y, H:i') : '';
            return response()->json([
                'valid'   => false,
                'message' => "Tiket ini SUDAH check-in sebelumnya" . ($when ? " pada {$when}" : '') . ".",
            ], 409);
        }

        if (!in_array($status, ['success', 'settlement'])) {
            return response()->json(['valid' => false, 'message' => 'Tiket belum lunas / status tidak valid: ' . $transaction->status], 422);
        }

        $transaction->update([
            'status'        => 'used',
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'valid'   => true,
            'message' => 'Check-in berhasil!',
            'data'    => [
                'name'  => $transaction->customer_name,
                'event' => $transaction->event->title ?? '-',
                'order_id' => $transaction->order_id,
            ],
        ]);
    }
}