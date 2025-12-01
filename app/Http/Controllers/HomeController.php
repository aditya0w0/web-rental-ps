<?php

namespace App\Http\Controllers;

use App\Models\PlaystationType;
use App\Models\Accessory;
use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $playstationTypes = PlaystationType::where('is_active', true)->get();
        $accessories = Accessory::where('is_active', true)->where('stock', '>', 0)->limit(8)->get();
        
        $articles = Article::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        if ($articles->isEmpty()) {
            $samples = [
                [
                    'title' => 'Tips Merawat Stick DualSense agar Awet',
                    'slug' => 'tips-merawat-stick-dualsense-agar-awet',
                    'excerpt' => 'Cara sederhana menjaga stick tetap prima untuk sesi gaming panjang.',
                    'body' => 'Bersihkan analog secara berkala, hindari menekan terlalu keras, dan simpan di tempat kering. Gunakan charging dock resmi agar arus stabil.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1601935113643-1d3e98d9c3ce?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now(),
                ],
                [
                    'title' => 'Game PS5 Terbaik untuk Multiplayer Keluarga',
                    'slug' => 'game-ps5-terbaik-untuk-multiplayer-keluarga',
                    'excerpt' => 'Rekomendasi seru untuk dimainkan bareng keluarga.',
                    'body' => 'Coba Sackboy: A Big Adventure, Overcooked! All You Can Eat, dan It Takes Two untuk kolaborasi menyenangkan.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now()->subDay(),
                ],
                [
                    'title' => 'Setting Jitu Koneksi Wi‑Fi untuk PS5',
                    'slug' => 'setting-jitu-koneksi-wifi-untuk-ps5',
                    'excerpt' => 'Minimkan lag saat main online dengan langkah praktis.',
                    'body' => 'Gunakan band 5 GHz, dekatkan konsol ke router, dan aktifkan QoS untuk port PlayStation agar prioritas bandwidth aman.',
                    'author_name' => 'PlayHub',
                    'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1600&auto=format&fit=crop',
                    'published_at' => now()->subDays(2),
                ],
            ];
            $articles = collect(array_map(fn($x) => (object) $x, $samples));
        }
        return view('home', compact('playstationTypes', 'accessories', 'articles'));
    }
}