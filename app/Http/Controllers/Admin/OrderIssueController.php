<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderIssue;
use Illuminate\Http\Request;

class OrderIssueController extends Controller
{
    public function index()
    {
        $issues = OrderIssue::with(['order.user'])->latest()->paginate(20);
        $issues->getCollection()->each->syncSlaStatus();

        return view('admin.order-issues.index', compact('issues'));
    }

    public function show(OrderIssue $issue)
    {
        $issue->load(['order.user', 'photos']);
        $issue->syncSlaStatus();

        return view('admin.order-issues.show', compact('issue'));
    }

    public function respond(Request $request, OrderIssue $issue)
    {
        $data = $request->validate([
            'admin_response' => 'required|string|min:5',
            'status' => 'required|in:open,in_progress,resolved,rejected',
        ]);
        if (!$issue->first_responded_at && filled($data['admin_response'])) {
            $data['first_responded_at'] = now();
        }

        if (in_array($data['status'], ['resolved', 'rejected'], true)) {
            $data['resolved_at'] = $issue->resolved_at ?? now();
        } elseif (in_array($issue->status, ['resolved', 'rejected'], true)) {
            $data['resolved_at'] = null;
        }

        $issue->fill($data);
        $issue->sla_status = $issue->refreshSlaStatus();
        $issue->save();

        if ($issue->user) {
            $issue->user->notify(new \App\Notifications\OrderIssueUpdated($issue));
        }

        return redirect()->route('admin.order-issues.show', $issue)->with('success','Response saved');
    }
}
