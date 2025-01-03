<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_tag', function (Blueprint $table) {
            $table->foreignUlid('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignUlid('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['movie_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_tag');
    }
};
