<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Liamtseva\Cinema\Enums\UserListType;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_lists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->ulidMorphs('listable');
            $table->timestamps();

            $table->unique(['user_id', 'listable_id', 'listable_type']);
        });

        Schema::table('user_lists', function (Blueprint $table) {
            $table->enumAlterColumn('type', 'user_list_type', UserListType::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_lists');

        DB::unprepared('DROP TYPE user_list_type');
    }
};
