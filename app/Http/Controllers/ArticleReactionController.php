<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleReaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleReactionController extends Controller
{
    public function toggle(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->is_published && $article->published_at, 404);

        $data = $request->validate([
            'type' => ['required', Rule::in(ArticleReaction::TYPES)],
        ]);

        $user = $request->user();
        $guestToken = $request->cookie('article_guest_token') ?: $request->session()->get('article_guest_token');

        if (!$user && !$guestToken) {
            $guestToken = Str::random(40);
            $request->session()->put('article_guest_token', $guestToken);
        }

        $query = ArticleReaction::query()
            ->where('article_id', $article->id)
            ->where('type', $data['type']);

        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('guest_token_hash', hash('sha256', $guestToken));
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $message = 'Reaksi dihapus.';
        } else {
            ArticleReaction::create([
                'article_id' => $article->id,
                'user_id' => $user?->id,
                'guest_token_hash' => $user ? null : hash('sha256', $guestToken),
                'type' => $data['type'],
            ]);
            $message = 'Reaksi tersimpan.';
        }

        $response = redirect()->route('articles.show', $article->slug)->with('success', $message);

        if (!$user) {
            $response->withCookie(Cookie::make('article_guest_token', $guestToken, 60 * 24 * 30));
        }

        return $response;
    }
}
