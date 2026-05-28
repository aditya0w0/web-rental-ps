<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderIssue;
use App\Services\PaymentProofAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.accessory')->latest()->paginate(10);
        $issues = OrderIssue::where('user_id', Auth::id())->latest()->limit(20)->get();
        return view('orders.index', compact('orders', 'issues'));
    }

    

    public function payment(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'This order cannot be paid for.');
        }
        if ($order->payment_proof) {
            return redirect()->route('orders.show', $order)->with('success', 'Payment proof already uploaded. Waiting for admin confirmation.');
        }
        $order->load('items.accessory');

        return view('orders.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order, PaymentProofAnalyzer $analyzer)
    {
        $this->authorize('update', $order);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'This order cannot be paid for.');
        }

        $file = $request->file('payment_proof');
        $analysis = $analyzer->analyze($file);
        $path = $file->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status' => 'pending',
            'payment_date' => null,
            ...$analysis,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Payment submitted successfully. Waiting for admin confirmation.');
    }
    
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        // Auto-cancel on view if expired without payment proof
        if (($order->status ?? 'pending') === 'pending' && !$order->payment_proof && $order->created_at && $order->created_at->lte(now()->subHour())) {
            foreach ($order->items as $item) {
                if ($item->accessory) {
                    $item->accessory->increment('stock', (int) $item->quantity);
                }
            }
            $order->update(['status' => 'failed', 'rejection_reason' => 'Auto-cancelled: payment timeout', 'payment_date' => null]);
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
            }
        }
        $order->load(['issues']);
        $order->issues->each->syncSlaStatus();

        return view('orders.show', compact('order'));
    }
}
