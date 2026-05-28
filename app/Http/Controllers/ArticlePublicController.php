<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticlePublicController extends Controller
{
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('articles.show', compact('article'));
    }
}
