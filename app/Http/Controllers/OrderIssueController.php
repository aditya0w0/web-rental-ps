<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderIssueController extends Controller
{
    public function index()
    {
        $issues = OrderIssue::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->paginate(15);

        $issues->getCollection()->each->syncSlaStatus();

        return view('orders.issues.index', compact('issues'));
    }

    public function create(Order $order)
    {
        $this->authorize('view', $order);
        if (!$this->canCreateIssue($order)) {
            return redirect()->route('orders.show', $order)->with('error', $this->issueUnavailableMessage($order));
        }

        return view('orders.issue', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $data = $request->validate([
            'type' => 'required|string',
            'description' => 'required|string|min:10',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'video_url' => 'nullable|url',
            'contact_phone' => 'required|string|min:8|max:20',
        ]);

        if (!$this->canCreateIssue($order)) {
            return redirect()->route('orders.show', $order)->with('error', $this->issueUnavailableMessage($order));
        }

        $paths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $paths[] = $file->store('order_issues', 'public');
            }
        }

        $issue = OrderIssue::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'description' => $data['description'],
            'photo_path' => null,
            'video_url' => $data['video_url'] ?? null,
            'contact_phone' => $data['contact_phone'],
            'status' => 'open',
        ]);

        foreach ($paths as $p) {
            \App\Models\OrderIssuePhoto::create(['order_issue_id' => $issue->id, 'path' => $p]);
        }

        $admins = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewOrderIssueSubmitted($issue));
        }

        return redirect()->route('orders.show', $order)->with('success', 'Keluhan telah dikirim. Admin akan menindaklanjuti.');
    }

    private function canCreateIssue(Order $order): bool
    {
        if (($order->fulfillment_status ?? 'none') !== 'completed' || !$order->delivered_at) {
            return false;
        }

        return now()->lte($order->delivered_at->copy()->addHours(24));
    }

    private function issueUnavailableMessage(Order $order): string
    {
        if (($order->fulfillment_status ?? 'none') !== 'completed' || !$order->delivered_at) {
            return 'Keluhan aktif setelah order selesai dan barang diterima.';
        }

        return 'Batas waktu pengajuan keluhan telah lewat (24 jam setelah order Completed).';
    }
}
