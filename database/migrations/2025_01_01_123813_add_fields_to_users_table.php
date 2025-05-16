<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->ulid('id')->primary();
            $table->string('name')->unique()->change();
            $table->string('password')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->enumAlterColumn('role', 'role', Role::class, default: Role::USER->value);
            $table->string('avatar', 2048)->nullable();
            $table->string('backdrop', 2048)->nullable();
            $table->enumAlterColumn('gender', 'gender', Gender::class, nullable: true);
            $table->string('description', 248)->nullable();
            $table->date('birthday')->nullable();
            $table->string('provider_id', 255)->nullable()->after('password');
            $table->string('provider_name', 255)->nullable()->after('provider_id');
            $table->string('provider_token', 255)->nullable()->after('provider_name');
            $table->string('provider_refresh_token', 255)->nullable()->after('provider_token');
            $table->boolean('allow_adult')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_auto_next')->default(false);
            $table->boolean('is_auto_play')->default(false);
            $table->boolean('is_auto_skip_intro')->default(false);
            $table->boolean('is_private_favorites')->default(false);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->foreignUlid('user_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->id();

            $table->dropColumn('role');
            $table->dropColumn('avatar');
            $table->dropColumn('backdrop');
            $table->dropColumn('gender');
            $table->dropColumn('description');
            $table->dropColumn('birthday');
            $table->dropColumn('provider_id');
            $table->dropColumn('provider_name');
            $table->dropColumn('provider_token');
            $table->dropColumn('provider_refresh_token');
            $table->dropColumn('allow_adult');
            $table->dropColumn('last_seen_at');
            $table->dropColumn('is_auto_next');
            $table->dropColumn('is_auto_play');
            $table->dropColumn('is_auto_skip_intro');
            $table->dropColumn('is_private_favorites');
        });

        DB::unprepared('DROP TYPE role');
        DB::unprepared('DROP TYPE gender');

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->foreignId('user_id')->nullable()->index();
        });
    }
};
