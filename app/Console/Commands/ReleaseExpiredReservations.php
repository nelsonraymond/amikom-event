<?php

namespace App\Console\Commands;

use App\Http\Controllers\CheckoutController;
use App\Models\Transaction;
use Illuminate\Console\Command;

class ReleaseExpiredReservations extends Command
{
    protected $signature = 'transactions:release-expired';
    protected $description = 'Melepas reservasi stok untuk transaksi pending yang sudah melewati batas waktu pembayaran';

    public function handle(CheckoutController $checkoutController)
    {
        $expired = Transaction::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $transaction) {
            $checkoutController->releaseReservation($transaction, 'expired');
        }

        $this->info($expired->count() . ' transaksi kadaluarsa telah diproses.');
    }
}