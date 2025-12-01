<?php

namespace Database\Seeders;

use App\Models\PlaystationUnit;
use App\Models\PlaystationType;
use Illuminate\Database\Seeder;

class PlaystationUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = PlaystationType::all();

        foreach ($types as $type) {
            // Buat 3 unit untuk setiap tipe
            for ($i = 1; $i <= 3; $i++) {
                PlaystationUnit::create([
                    'playstation_type_id' => $type->id,
                    'unit_code' => strtoupper(str_replace(' ', '', $type->name)) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'serial_number' => 'SN' . strtoupper(uniqid()),
                    'status' => 'available',
                    'condition_notes' => 'Unit dalam kondisi baik'
                ]);
            }
        }
    }
}