<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_issue_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_issue_id');
            $table->string('path');
            $table->timestamps();
            $table->foreign('order_issue_id')->references('id')->on('order_issues')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_issue_photos');
    }
};