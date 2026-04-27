<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard with preloaded summary data.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $today = today();

        if ($user->role === 'sales') {
            $reportsToday = $user->reports()
                ->whereDate('tanggal', $today)
                ->withCount('details')
                ->get();

            $stats = [
                'reportsTodayCount' => $reportsToday->count(),
                'visitsTodayCount' => $reportsToday->sum('details_count'),
                'totalReportsCount' => $user->reports()->count(),
            ];

            $recentReports = $user->reports()
                ->with([
                    'user',
                    'details:id,report_id,outlet',
                ])
                ->withCount('details')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats = [
                'totalSalesCount' => User::where('role', 'sales')->count(),
                'reportsTodayCount' => Report::whereDate('tanggal', $today)->count(),
                'totalReportsCount' => Report::count(),
            ];

            $recentReports = Report::query()
                ->with([
                    'user',
                    'details:id,report_id,outlet',
                ])
                ->withCount('details')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentReports'));
    }
}
