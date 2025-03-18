<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('movie_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('slug', 128)->unique();
            $table->string('name', 128);
            $table->text('description', 512)->nullable();
            $table->unsignedSmallInteger('duration')->nullable();
            $table->date('air_date')->nullable();
            $table->boolean('is_filler')->default(false);
            $table->json('pictures')->default(DB::raw("'[]'::json"));
            $table->json('video_players')->default(DB::raw("'[]'::json"));
            $table->string('meta_title', 128)->nullable();
            $table->string('meta_description', 376)->nullable();
            $table->string('meta_image', 2048)->nullable();
            $table->timestamps();

            $table->unique(['movie_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
