<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof_original_name')->nullable()->after('payment_proof');
            $table->string('payment_proof_provider')->nullable()->after('payment_proof_original_name');
            $table->unsignedTinyInteger('payment_proof_confidence')->default(0)->after('payment_proof_provider');
            $table->string('payment_proof_risk')->default('not_checked')->after('payment_proof_confidence');
            $table->json('payment_proof_flags')->nullable()->after('payment_proof_risk');
            $table->timestamp('payment_proof_analyzed_at')->nullable()->after('payment_proof_flags');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->string('payment_proof_original_name')->nullable()->after('payment_proof');
            $table->string('payment_proof_provider')->nullable()->after('payment_proof_original_name');
            $table->unsignedTinyInteger('payment_proof_confidence')->default(0)->after('payment_proof_provider');
            $table->string('payment_proof_risk')->default('not_checked')->after('payment_proof_confidence');
            $table->json('payment_proof_flags')->nullable()->after('payment_proof_risk');
            $table->timestamp('payment_proof_analyzed_at')->nullable()->after('payment_proof_flags');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_original_name',
                'payment_proof_provider',
                'payment_proof_confidence',
                'payment_proof_risk',
                'payment_proof_flags',
                'payment_proof_analyzed_at',
            ]);
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_original_name',
                'payment_proof_provider',
                'payment_proof_confidence',
                'payment_proof_risk',
                'payment_proof_flags',
                'payment_proof_analyzed_at',
            ]);
        });
    }
};
