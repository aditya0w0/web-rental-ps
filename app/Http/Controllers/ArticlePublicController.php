<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleReaction;

class ArticlePublicController extends Controller
{
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $comments = $article->comments()
            ->visible()
            ->with('user')
            ->oldest()
            ->get();

        $reactionTypes = ArticleReaction::TYPES;
        $reactionCounts = $article->reactions()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $activeReactions = collect();
        $user = request()->user();
        $guestToken = request()->cookie('article_guest_token') ?: request()->session()->get('article_guest_token');

        if ($user) {
            $activeReactions = $article->reactions()
                ->where('user_id', $user->id)
                ->pluck('type');
        } elseif ($guestToken) {
            $activeReactions = $article->reactions()
                ->where('guest_token_hash', hash('sha256', $guestToken))
                ->pluck('type');
        }

        $guestIdentity = [
            'name' => request()->cookie('article_guest_name'),
            'email' => request()->cookie('article_guest_email'),
        ];

        return view('articles.show', compact(
            'article',
            'comments',
            'reactionTypes',
            'reactionCounts',
            'activeReactions',
            'guestIdentity'
        ));
    }
}
