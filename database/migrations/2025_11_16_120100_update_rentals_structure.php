<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'start_time')) {
                $table->dateTime('start_time')->after('playstation_type_id');
            }
            if (!Schema::hasColumn('rentals', 'end_time')) {
                $table->dateTime('end_time')->after('start_time');
            }
            if (!Schema::hasColumn('rentals', 'duration_type')) {
                $table->enum('duration_type', ['hour', 'day'])->after('end_time');
            }
            if (!Schema::hasColumn('rentals', 'duration_value')) {
                $table->integer('duration_value')->after('duration_type');
            }
            if (!Schema::hasColumn('rentals', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0)->after('duration_value');
            }
            if (!Schema::hasColumn('rentals', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending')->after('total_price');
            }
            if (!Schema::hasColumn('rentals', 'pickup_method')) {
                $table->enum('pickup_method', ['store_pickup', 'delivery'])->after('status');
            }
            if (!Schema::hasColumn('rentals', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('pickup_method');
            }
            if (!Schema::hasColumn('rentals', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('delivery_address');
            }
            if (!Schema::hasColumn('rentals', 'notes')) {
                $table->text('notes')->nullable()->after('phone_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            foreach ([
                'notes',
                'phone_number',
                'delivery_address',
                'pickup_method',
                'status',
                'total_price',
                'duration_value',
                'duration_type',
                'end_time',
                'start_time',
            ] as $col) {
                if (Schema::hasColumn('rentals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

