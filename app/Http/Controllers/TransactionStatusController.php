<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TransactionStatusController extends Controller
{
    public function track(Request $request)
    {
        $orderNumber = $request->input('order_number');

        if (!$orderNumber) {
            $recentOrders = Auth::check() ? Order::where('user_id', Auth::id())->latest()->limit(10)->get() : collect();
            return view('tracking.form', compact('recentOrders'));
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return view('tracking.not_found', ['order_number' => $orderNumber]);
        }

        return view('tracking.show', compact('order'));
    }
}
