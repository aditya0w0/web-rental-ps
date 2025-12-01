<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Rental;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $recentOrders = Order::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        $recentRentals = Rental::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'orders_total' => Order::where('user_id', $userId)->count(),
            'orders_completed' => Order::where('user_id', $userId)->where('fulfillment_status', 'completed')->count(),
            'rentals_total' => Rental::where('user_id', $userId)->count(),
            'rentals_active' => Rental::where('user_id', $userId)->where('status', 'active')->count(),
        ];

        return view('user.dashboard', compact('stats', 'recentOrders', 'recentRentals'));
    }
}