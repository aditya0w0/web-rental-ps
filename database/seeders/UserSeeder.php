<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin user
        User::create([
            'name' => 'Admin PlayHub',
            'email' => 'admin@playhub.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta'
        ]);

        // Buat beberapa customer users
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081234567891',
                'address' => 'Jl. Merdeka No. 10, Jakarta'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 20, Jakarta'
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081234567893',
                'address' => 'Jl. Gatot Subroto No. 30, Jakarta'
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}