<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Liamtseva\Cinema\Enums\RoomStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('episode_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_private')->default(false);
            $table->string('password')->nullable();
            $table->integer('max_viewers')->default(10);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->enumAlterColumn('room_status', 'room_status', RoomStatus::class, RoomStatus::NOT_STARTED->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
