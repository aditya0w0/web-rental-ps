<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('published_at', 'desc')->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'body' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:3072',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date_format:Y-m-d\\TH:i',
        ]);

        $slug = Str::slug($data['title']);
        if (Article::where('slug', $slug)->exists()) {
            $slug .= '-' . substr(uniqid(), -5);
        }
        $data['slug'] = $slug;

        if (isset($data['image'])) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $data['author_name'] = $data['author_name'] ?: ($request->user()->name ?? 'PlayHub');
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && !empty($data['published_at'])) {
            $data['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['published_at']);
        } elseif ($data['is_published']) {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        Article::create($data);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'body' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:3072',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date_format:Y-m-d\\TH:i',
        ]);

        $data['slug'] = $article->slug;
        if ($article->title !== $data['title']) {
            $slug = Str::slug($data['title']);
            if (Article::where('slug', $slug)->where('id', '<>', $article->id)->exists()) {
                $slug .= '-' . substr(uniqid(), -5);
            }
            $data['slug'] = $slug;
        }

        if (isset($data['image'])) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $data['author_name'] = $data['author_name'] ?: ($request->user()->name ?? 'PlayHub');
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && !empty($data['published_at'])) {
            $data['published_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['published_at']);
        } elseif ($data['is_published']) {
            $data['published_at'] = $article->published_at ?: now();
        } else {
            $data['published_at'] = null;
        }

        $article->update($data);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel dihapus');
    }
}
