<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playstation_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playstation_type_id')->constrained()->onDelete('cascade');
            $table->string('unit_code')->unique();
            $table->string('serial_number')->unique();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->text('condition_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playstation_units');
    }
};