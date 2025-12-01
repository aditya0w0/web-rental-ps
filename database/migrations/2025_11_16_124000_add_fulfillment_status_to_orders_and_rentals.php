<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'fulfillment_status')) {
                $table->enum('fulfillment_status', ['none','processing','shipped','ready_for_pickup','completed'])->default('none')->after('status');
            }
        });

        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'fulfillment_status')) {
                $table->enum('fulfillment_status', ['none','processing','delivering','ready_for_pickup','completed'])->default('none')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'fulfillment_status')) {
                $table->dropColumn('fulfillment_status');
            }
        });

        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'fulfillment_status')) {
                $table->dropColumn('fulfillment_status');
            }
        });
    }
};