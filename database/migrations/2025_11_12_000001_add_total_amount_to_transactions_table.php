<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('transactions', 'total_amount')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('transactions', 'total_amount')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
