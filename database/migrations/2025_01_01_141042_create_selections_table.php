<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('selections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name', 128);
            $table->text('description')->nullable();
            $table->string('meta_title', 128)->nullable();
            $table->string('meta_description', 376)->nullable();
            $table->string('meta_image', 2048)->nullable();
            $table->timestamps();
        });

        DB::unprepared("
            ALTER TABLE selections
            ADD COLUMN searchable tsvector GENERATED ALWAYS AS (
                setweight(to_tsvector('ukrainian', name), 'A') ||
                setweight(to_tsvector('ukrainian', coalesce(description, '')), 'B')
            ) STORED
        ");

        DB::unprepared('CREATE INDEX selections_searchable_index ON selections USING GIN (searchable)');
        DB::unprepared('CREATE INDEX selections_trgm_name_idx ON selections USING GIN (name gin_trgm_ops)');
    }

    public function down(): void
    {
        DB::unprepared('DROP INDEX IF EXISTS selections_searchable_index');
        DB::unprepared('DROP INDEX IF EXISTS selections_trgm_name_idx');

        Schema::dropIfExists('selections');
    }
};
