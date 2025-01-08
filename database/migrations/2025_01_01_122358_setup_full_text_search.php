<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $tsearchPath = env('POSTGRES_TSEARCH_PATH');

        // Перевіряємо, чи шлях до PostgreSQL вказаний
        if (!$tsearchPath) {
            throw new RuntimeException('POSTGRES_TSEARCH_PATH не вказано у файлі .env');
        }

        // Копіюємо файли для повнотекстового пошуку
        $files = [
            'uk_ua.affix' => "$tsearchPath/uk_ua.affix",
            'uk_ua.dict' => "$tsearchPath/uk_ua.dict",
            'ukrainian.stop' => "$tsearchPath/ukrainian.stop",
        ];

        foreach ($files as $source => $destination) {
            $sourcePath = base_path("database/fts-dict/$source");

            // Перевіряємо, чи існує вихідний файл
            if (!file_exists($sourcePath)) {
                throw new RuntimeException("Файл $sourcePath не знайдено");
            }

            // Перевіряємо, чи файл уже існує в цільовому місці
            if (file_exists($destination)) {
                // Пропускаємо копіювання, якщо файл вже існує
                continue;
            }

            // Копіюємо файл
            if (!copy($sourcePath, $destination)) {
                throw new RuntimeException("Не вдалося скопіювати $sourcePath до $destination");
            }
        }
        // Видаляємо існуючі словники та конфігурації, якщо вони є
        DB::statement('DROP TEXT SEARCH DICTIONARY IF EXISTS ukrainian_huns CASCADE');
        DB::statement('DROP TEXT SEARCH DICTIONARY IF EXISTS ukrainian_stem CASCADE');
        DB::statement('DROP TEXT SEARCH CONFIGURATION IF EXISTS ukrainian CASCADE');
        // Виконуємо SQL-команди
        DB::statement('
            CREATE TEXT SEARCH DICTIONARY ukrainian_huns (
                TEMPLATE = ispell,
                DictFile = uk_UA,
                AffFile = uk_UA,
                StopWords = ukrainian
            );
        ');

        DB::statement('
            CREATE TEXT SEARCH DICTIONARY ukrainian_stem (
                template = simple,
                stopwords = ukrainian
            );
        ');

        DB::statement('
            CREATE TEXT SEARCH CONFIGURATION ukrainian (PARSER=default);
        ');

        DB::statement('
            ALTER TEXT SEARCH CONFIGURATION ukrainian ALTER MAPPING FOR
                hword, hword_part, word WITH ukrainian_huns, ukrainian_stem;
        ');

        DB::statement('
            ALTER TEXT SEARCH CONFIGURATION ukrainian ALTER MAPPING FOR
                int, uint, numhword, numword, hword_numpart, email, float, file, url, url_path, version, host, sfloat WITH simple;
        ');

        DB::statement('
            ALTER TEXT SEARCH CONFIGURATION ukrainian ALTER MAPPING FOR
                asciihword, asciiword, hword_asciipart WITH english_stem;
        ');
        DB::unprepared('CREATE EXTENSION IF NOT EXISTS pg_trgm');
    }

    public function down(): void
    {
        DB::statement('DROP TEXT SEARCH CONFIGURATION IF EXISTS ukrainian;');
        DB::statement('DROP TEXT SEARCH DICTIONARY IF EXISTS ukrainian_huns;');
        DB::statement('DROP TEXT SEARCH DICTIONARY IF EXISTS ukrainian_stem;');
    }
};
