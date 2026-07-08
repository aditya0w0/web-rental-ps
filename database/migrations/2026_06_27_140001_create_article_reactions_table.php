<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('guest_token_hash')->nullable();
            $table->string('type');
            $table->timestamps();

            $table->unique(['article_id', 'user_id', 'type']);
            $table->unique(['article_id', 'guest_token_hash', 'type']);
            $table->index(['article_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_reactions');
    }
};
