<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        return view('admin.dashboard', [
            'todayCount' => Booking::whereDate('starts_at', $today)->whereIn('status', ['pending', 'confirmed'])->count(),
            'pendingCount' => Booking::where('status', 'pending')->where('starts_at', '>=', now())->count(),
            'weekCount' => Booking::whereBetween('starts_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->whereNotIn('status', ['cancelled'])->count(),
            'monthCount' => Booking::whereBetween('starts_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->whereNotIn('status', ['cancelled'])->count(),
            'upcoming' => Booking::with(['service', 'staff'])
                ->whereIn('status', ['pending', 'confirmed'])->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')->limit(8)->get(),
        ]);
    }
}
