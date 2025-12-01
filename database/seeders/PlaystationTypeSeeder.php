<?php

namespace Database\Seeders;

use App\Models\PlaystationType;
use Illuminate\Database\Seeder;

class PlaystationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'PlayStation 5',
                'description' => 'Konsol game generasi terbaru dari Sony dengan performa tinggi',
                'rental_price_per_hour' => 15000,
                'rental_price_per_day' => 120000,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 4 Pro',
                'description' => 'PS4 dengan performa enhanced dan grafis 4K',
                'rental_price_per_hour' => 10000,
                'rental_price_per_day' => 80000,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 4 Slim',
                'description' => 'PS4 versi slim yang lebih ringkas dan hemat energi',
                'rental_price_per_hour' => 8000,
                'rental_price_per_day' => 60000,
                'is_active' => true
            ],
            [
                'name' => 'PlayStation 3',
                'description' => 'Konsol klasik dengan banyak game legendaris',
                'rental_price_per_hour' => 5000,
                'rental_price_per_day' => 35000,
                'is_active' => true
            ]
        ];

        foreach ($types as $type) {
            PlaystationType::create($type);
        }
    }
}