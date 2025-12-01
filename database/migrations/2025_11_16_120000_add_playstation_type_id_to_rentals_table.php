<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'playstation_type_id')) {
                $table->foreignId('playstation_type_id')
                    ->after('playstation_unit_id')
                    ->constrained('playstation_types')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'playstation_type_id')) {
                $table->dropConstrainedForeignId('playstation_type_id');
            }
        });
    }
};

