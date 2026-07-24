<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleCommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $comments = ArticleComment::with(['article', 'user', 'moderator'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = ArticleComment::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.article-comments.index', compact('comments', 'counts', 'status'));
    }

    public function approve(ArticleComment $comment): RedirectResponse
    {
        $comment->approve(request()->user());

        return back()->with('success', 'Komentar disetujui.');
    }

    public function flag(Request $request, ArticleComment $comment): RedirectResponse
    {
        $data = $request->validate([
            'moderation_note' => ['nullable', 'string', 'max:500'],
        ]);

        $comment->flag($request->user(), $data['moderation_note'] ?? null);

        return back()->with('success', 'Komentar ditandai untuk pengecekan.');
    }

    public function destroyTrace(Request $request, ArticleComment $comment): RedirectResponse
    {
        $data = $request->validate([
            'moderation_note' => ['nullable', 'string', 'max:500'],
        ]);

        $comment->deleteWithTrace($request->user(), $data['moderation_note'] ?? null);

        return redirect()->route('admin.article-comments.index')->with('success', 'Komentar dihapus dengan jejak moderasi.');
    }
}
