<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessories = [
            // Stik/Controller
            [
                'name' => 'DualSense Wireless Controller PS5',
                'category' => 'controller',
                'description' => 'Controller wireless resmi untuk PS5 dengan haptic feedback',
                'price' => 1200000,
                'stock' => 10,
                'brand' => 'Sony',
                'image' => 'images/products/dualsense-wireless-controller-ps5.png',
                'is_active' => true
            ],
            [
                'name' => 'DualShock 4 Wireless Controller',
                'category' => 'controller',
                'description' => 'Controller wireless untuk PS4',
                'price' => 800000,
                'stock' => 15,
                'brand' => 'Sony',
                'image' => 'images/products/dualshock-4-wireless-controller.png',
                'is_active' => true
            ],
            // Kabel
            [
                'name' => 'HDMI Cable 2.1 2 Meter',
                'category' => 'cable',
                'description' => 'Kabel HDMI high-speed untuk PS5 dan PS4',
                'price' => 150000,
                'stock' => 20,
                'brand' => 'Belkin',
                'image' => 'images/products/hdmi-cable-2-1-2-meter.png',
                'is_active' => true
            ],
            [
                'name' => 'USB-C Charging Cable PS5',
                'category' => 'cable',
                'description' => 'Kabel pengisi daya untuk controller PS5',
                'price' => 75000,
                'stock' => 25,
                'brand' => 'Sony',
                'image' => 'images/products/usb-c-charging-cable-ps5.png',
                'is_active' => true
            ],
            // Headset
            [
                'name' => 'Pulse 3D Wireless Headset',
                'category' => 'headset',
                'description' => 'Headset wireless khusus untuk PS5 dengan audio 3D',
                'price' => 1800000,
                'stock' => 8,
                'brand' => 'Sony',
                'image' => 'images/products/pulse-3d-wireless-headset.png',
                'is_active' => true
            ],
            [
                'name' => 'Gaming Headset HyperX Cloud Stinger',
                'category' => 'headset',
                'description' => 'Headset gaming dengan kualitas suara jernih',
                'price' => 600000,
                'stock' => 12,
                'brand' => 'HyperX',
                'image' => 'images/products/gaming-headset-hyperx-cloud-stinger.png',
                'is_active' => true
            ],
            // Kaset Game
            [
                'name' => 'Game FIFA 24 PS5',
                'category' => 'game',
                'description' => 'Game sepakbola terbaru untuk PS5',
                'price' => 900000,
                'stock' => 5,
                'brand' => 'EA Sports',
                'image' => 'images/products/game-fifa-24-ps5.png',
                'is_active' => true
            ],
            [
                'name' => 'Game Spider-Man 2 PS5',
                'category' => 'game',
                'description' => 'Game action adventure Spider-Man untuk PS5',
                'price' => 800000,
                'stock' => 7,
                'brand' => 'Insomniac Games',
                'image' => 'images/products/game-spider-man-2-ps5.png',
                'is_active' => true
            ],
            // Lainnya
            [
                'name' => 'PS5 DualSense Charging Station',
                'category' => 'accessory',
                'description' => 'Stasiun pengisian daya untuk 2 controller PS5',
                'price' => 450000,
                'stock' => 6,
                'brand' => 'Sony',
                'image' => 'images/products/ps5-dualsense-charging-station.png',
                'is_active' => true
            ],
            [
                'name' => 'PS5 Media Remote',
                'category' => 'accessory',
                'description' => 'Remote control untuk PS5 saat digunakan sebagai media player',
                'price' => 350000,
                'stock' => 8,
                'brand' => 'Sony',
                'image' => 'images/products/ps5-media-remote.png',
                'is_active' => true
            ]
        ];

        foreach ($accessories as $accessory) {
            Accessory::updateOrCreate(['name' => $accessory['name']], $accessory);
        }
    }
}
