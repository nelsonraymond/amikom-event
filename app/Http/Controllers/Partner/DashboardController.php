<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $eventIds = $partner->events->pluck('id');

        $events = $partner->events()->withCount('reviews')->latest()->get();

        $totalRevenue = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', ['success', 'settlement', 'used'])
            ->sum('total_price');

        $totalTicketsSold = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', ['success', 'settlement', 'used'])
            ->count();

        // --- DATA GRAFIK: penjualan tiket per hari, 30 hari terakhir ---
        $salesPerDay = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', ['success', 'settlement', 'used'])
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as sale_date, COUNT(*) as total')
            ->groupBy('sale_date')
            ->pluck('total', 'sale_date'); // hasil: ['2026-07-15' => 3, ...]

        // Isi tanggal yang kosong (tanpa transaksi) dengan 0, biar grafik gak bolong
        $chartLabels = [];
        $chartData   = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key  = $date->format('Y-m-d');

            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[]   = $salesPerDay[$key] ?? 0;
        }

        return view('partner.dashboard', compact(
            'partner', 'events', 'totalRevenue', 'totalTicketsSold',
            'chartLabels', 'chartData'
        ));
    }
}