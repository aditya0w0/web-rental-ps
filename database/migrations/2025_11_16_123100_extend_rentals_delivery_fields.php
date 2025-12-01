<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'delivery_city')) {
                $table->string('delivery_city')->nullable()->after('delivery_address');
            }
            if (!Schema::hasColumn('rentals', 'delivery_distance_km')) {
                $table->integer('delivery_distance_km')->nullable()->after('delivery_city');
            }
            if (!Schema::hasColumn('rentals', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('delivery_distance_km');
            }
            if (!Schema::hasColumn('rentals', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('payment_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            foreach (['rejection_reason','delivery_fee','delivery_distance_km','delivery_city'] as $col) {
                if (Schema::hasColumn('rentals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};