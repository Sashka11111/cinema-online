<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('comment_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_liked'); // true = liked, false = disliked
            $table->timestamps();

            $table->unique(['comment_id', 'user_id']); // Запобігає дублюванню лайку чи дизлайку від одного користувача
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};
