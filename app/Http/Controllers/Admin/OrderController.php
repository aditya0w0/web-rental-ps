<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.accessory'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.accessory', 'issues']);
        return view('admin.orders.show', compact('order'));
    }

    public function confirmPayment(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order status is not pending.');
        }
        $order->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
        }
        return back()->with('success', 'Payment confirmed.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order status is not pending.');
        }
        $request->validate(['reason' => 'required|string|min:5']);
        $order->update([
            'status' => 'failed',
            'rejection_reason' => $request->reason,
            'payment_date' => null,
        ]);
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
        }
        return back()->with('success', 'Payment rejected.');
    }

    public function updateFulfillment(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:none,processing,shipped,ready_for_pickup,completed']);
        $order->update(['fulfillment_status' => $request->status]);
        if ($request->status === 'completed') {
            $order->update(['delivered_at' => now()]);
        }
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
        }
        return back()->with('success', 'Fulfillment status updated.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        if ($order->pickup_method !== 'delivery') {
            return back()->with('error', 'Tracking number only for delivery orders.');
        }
        $data = $request->validate([
            'tracking_number' => 'nullable|string|max:255'
        ]);
        $order->update(['tracking_number' => $data['tracking_number'] ?? null]);
        return back()->with('success', 'Tracking number updated.');
    }
}
