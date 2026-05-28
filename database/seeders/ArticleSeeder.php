<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Panduan Memilih Durasi Rental PlayStation untuk Acara Rumah',
                'slug' => 'panduan-memilih-durasi-rental-playstation-untuk-acara-rumah',
                'excerpt' => 'Cara menghitung durasi sewa yang masuk akal untuk main santai, turnamen kecil, atau acara keluarga.',
                'body' => "Durasi rental paling aman dimulai dari rencana jumlah pemain dan jenis game yang akan dimainkan. Untuk sesi santai dua sampai empat orang, paket per jam biasanya cukup kalau game yang dipilih ringan seperti FIFA, Tekken, atau game party. Untuk acara keluarga, ulang tahun, atau kumpul komunitas, paket harian lebih nyaman karena tidak membuat pemain terburu-buru.\n\nPerhatikan juga waktu setup. Konsol perlu ditempatkan dekat TV, controller perlu dicek baterainya, dan akun game perlu disiapkan sebelum tamu datang. Sisakan minimal tiga puluh menit sebelum acara dimulai agar sesi bermain tidak terpotong urusan teknis.\n\nJika pemain bergantian, pilih durasi yang memberi ruang jeda. Game kompetitif biasanya cepat panas, sementara game co-op seperti It Takes Two atau Overcooked lebih enak dimainkan dalam blok waktu panjang. Dengan rencana durasi yang jelas, biaya rental lebih mudah dikontrol dan pengalaman bermain terasa lebih rapi.",
                'author_name' => 'Tim PlayHub',
                'image' => 'images/ps5/ps5.jpg.png',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Checklist Sebelum Konsol Diantar ke Lokasi Pelanggan',
                'slug' => 'checklist-sebelum-konsol-diantar-ke-lokasi-pelanggan',
                'excerpt' => 'Daftar singkat supaya unit, kabel, controller, dan game siap sebelum rental dimulai.',
                'body' => "Sebelum unit keluar dari toko, pastikan kode unit, kabel HDMI, kabel power, controller, dan aksesoris tambahan sudah dicatat. Foto kondisi fisik konsol dan controller juga membantu jika ada pengecekan ulang saat pengembalian.\n\nUntuk pengiriman, minta pelanggan menyiapkan TV dengan port HDMI kosong dan stopkontak yang aman. Jika memakai internet rumah, koneksi 5 GHz biasanya lebih stabil untuk download update dan bermain online, tetapi kabel LAN tetap menjadi pilihan terbaik jika tersedia.\n\nSaat sampai di lokasi, lakukan uji singkat: nyalakan konsol, buka satu game, cek tombol controller, dan pastikan audio keluar dari TV. Checklist sederhana seperti ini mengurangi komplain dan membuat proses rental terasa profesional.",
                'author_name' => 'Tim Operasional PlayHub',
                'image' => 'images/carousel/DHIMAS DHIKA PS_20240602_215411_0000.png',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Game Multiplayer yang Cocok untuk Rental PS5',
                'slug' => 'game-multiplayer-yang-cocok-untuk-rental-ps5',
                'excerpt' => 'Rekomendasi genre game yang aman untuk tamu dengan pengalaman bermain berbeda-beda.',
                'body' => "Untuk acara ramai, pilih game yang mudah dipahami dalam beberapa menit. Game sepak bola dan fighting cocok untuk kompetisi cepat karena satu ronde tidak terlalu lama. Game party cocok untuk keluarga karena kontrolnya sederhana dan suasananya lebih santai.\n\nJika pemainnya sudah terbiasa, game balap dan shooter bisa jadi pilihan, tetapi siapkan waktu untuk pengaturan kontrol. Untuk pasangan atau dua pemain yang ingin cerita panjang, game co-op naratif lebih cocok dibanding game kompetitif.\n\nKunci memilih game rental adalah ritme acara. Jangan hanya memilih game paling populer, pilih game yang membuat orang mudah ikut bermain tanpa harus belajar terlalu lama.",
                'author_name' => 'PlayHub Editorial',
                'image' => 'images/carousel/DHIMAS DHIKA PS_20240604_010659_0000.png',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Cara Merawat Controller Selama Masa Rental',
                'slug' => 'cara-merawat-controller-selama-masa-rental',
                'excerpt' => 'Kebiasaan kecil yang menjaga analog, tombol, dan baterai controller tetap aman.',
                'body' => "Controller adalah bagian yang paling sering dipakai selama rental, jadi perlakuannya perlu lebih hati-hati. Hindari menaruh controller di lantai, dekat minuman, atau di bawah bantal karena tombol bisa tertekan terus menerus tanpa sadar.\n\nSaat bermain game kompetitif, tekan tombol secukupnya. Menekan analog terlalu keras tidak membuat karakter bergerak lebih cepat, tetapi bisa mempercepat aus pada mekanisme analog. Jika tangan berkeringat, lap controller secara berkala dengan kain kering.\n\nSetelah selesai bermain, isi daya controller dan simpan di tempat terbuka yang aman. Kebiasaan sederhana ini membantu unit tetap nyaman untuk pelanggan berikutnya dan mengurangi biaya perawatan.",
                'author_name' => 'Tim PlayHub',
                'image' => 'images/carousel/Black Blue Illustrated Biker Astronaut T-Shirt_20240602_223910_0000.png',
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
