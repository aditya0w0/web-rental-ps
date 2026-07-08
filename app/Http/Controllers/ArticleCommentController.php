<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class ArticleCommentController extends Controller
{
    public function store(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->is_published && $article->published_at, 404);

        $user = $request->user();
        $payload = ['body' => $request->input('body')];
        $rules = ['body' => ['required', 'string', 'min:3', 'max:1000']];

        if (!$user) {
            $payload['guest_name'] = trim((string) ($request->input('guest_name') ?: $request->cookie('article_guest_name')));
            $payload['guest_email'] = trim((string) ($request->input('guest_email') ?: $request->cookie('article_guest_email')));
            $rules['guest_name'] = ['required', 'string', 'max:80'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
        }

        $data = Validator::make($payload, $rules)->validate();
        $isRisky = $this->looksRisky($data['body']);
        $status = $user && !$isRisky ? ArticleComment::STATUS_APPROVED : ArticleComment::STATUS_PENDING;

        ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $user?->id,
            'guest_name' => $user ? null : $data['guest_name'],
            'guest_email' => $user ? null : $data['guest_email'],
            'body' => $data['body'],
            'status' => $status,
            'approved_at' => $status === ArticleComment::STATUS_APPROVED ? now() : null,
            'ip_hash' => $request->ip() ? hash('sha256', $request->ip()) : null,
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        $message = $status === ArticleComment::STATUS_APPROVED
            ? 'Komentar kamu sudah tampil.'
            : 'Komentar dikirim dan menunggu pengecekan admin.';

        $response = redirect()->route('articles.show', $article->slug)->with('success', $message);

        if (!$user) {
            $response->withCookie(Cookie::make('article_guest_name', $data['guest_name'], 60 * 24 * 30));
            $response->withCookie(Cookie::make('article_guest_email', $data['guest_email'], 60 * 24 * 30));
        }

        return $response;
    }

    private function looksRisky(string $body): bool
    {
        return preg_match('/https?:\/\//i', $body) === 1
            || preg_match_all('/\b(?:promo|casino|slot|pinjol)\b/i', $body) > 0;
    }
}
