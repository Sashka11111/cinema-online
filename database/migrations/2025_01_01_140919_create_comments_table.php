<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulidMorphs('commentable'); // Поліморфний зв'язок (коментар може бути до різних моделей)
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete(); // Зовнішній ключ на користувача
            $table->boolean('is_spoiler')->default(false);
            $table->text('body'); // Текст коментаря
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignUlid('parent_id')->nullable()->constrained('comments')->cascadeOnDelete(); // FK на батьківський коментар для вкладених коментарів
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']); // Видаляємо зовнішній ключ
            $table->dropColumn('parent_id'); // Видаляємо сам стовпець
        });

        Schema::dropIfExists('comments');
    }
};
