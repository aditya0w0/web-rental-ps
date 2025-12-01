<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticlePublicController extends Controller
{
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            $samples = [
                'tips-merawat-stick-dualsense-agar-awet' => [
                    'title' => 'Tips Merawat Stick DualSense agar Awet',
                    'excerpt' => 'Cara sederhana menjaga stick tetap prima untuk sesi gaming panjang.',
                    'body' => 'Bersihkan analog secara berkala, hindari menekan terlalu keras, dan simpan di tempat kering. Gunakan charging dock resmi agar arus stabil.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1601935113643-1d3e98d9c3ce?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now(),
                ],
                'game-ps5-terbaik-untuk-multiplayer-keluarga' => [
                    'title' => 'Game PS5 Terbaik untuk Multiplayer Keluarga',
                    'excerpt' => 'Rekomendasi seru untuk dimainkan bareng keluarga.',
                    'body' => 'Coba Sackboy: A Big Adventure, Overcooked! All You Can Eat, dan It Takes Two untuk kolaborasi menyenangkan.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now()->subDay(),
                ],
                'setting-jitu-koneksi-wifi-untuk-ps5' => [
                    'title' => 'Setting Jitu Koneksi Wi‑Fi untuk PS5',
                    'excerpt' => 'Minimkan lag saat main online dengan langkah praktis.',
                    'body' => 'Gunakan band 5 GHz, dekatkan konsol ke router, dan aktifkan QoS untuk port PlayStation agar prioritas bandwidth aman.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now()->subDays(2),
                ],
            ];
            if (isset($samples[$slug])) {
                $a = (object) $samples[$slug];
                return view('articles.show', ['article' => $a]);
            }
            abort(404);
        }
        return view('articles.show', compact('article'));
    }
}