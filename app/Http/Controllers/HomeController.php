<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Article;
use App\Models\PlaystationType;

class HomeController extends Controller
{
    public function index()
    {
        $playstationTypes = PlaystationType::where('is_active', true)->get();
        $accessories = Accessory::where('is_active', true)
            ->where('stock', '>', 0)
            ->limit(8)
            ->get();

        $articles = Article::where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact('playstationTypes', 'accessories', 'articles'));
    }
}
