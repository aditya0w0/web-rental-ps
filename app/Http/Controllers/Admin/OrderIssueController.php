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
        return view('admin.order-issues.index', compact('issues'));
    }

    public function show(OrderIssue $issue)
    {
        $issue->load(['order.user']);
        return view('admin.order-issues.show', compact('issue'));
    }

    public function respond(Request $request, OrderIssue $issue)
    {
        $data = $request->validate([
            'admin_response' => 'required|string|min:5',
            'status' => 'required|in:open,in_progress,resolved,rejected',
        ]);
        $issue->update($data);
        if ($issue->user) {
            $issue->user->notify(new \App\Notifications\OrderIssueUpdated($issue));
        }
        return redirect()->route('admin.order-issues.show', $issue)->with('success','Response saved');
    }
}