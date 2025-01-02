<?php

namespace Liamtseva\Cinema\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        Blueprint::macro('enumAlterColumn',
            function (string $columnName,
                      string $enumTypeName,
                      string $enumClass,
                      ?string $default = null,
                      bool $nullable = false) {
                // Генеруємо список значень enum
                $value = collect($enumClass::cases())
                    ->map(fn ($case) => "'{$case->value}'")
                    ->implode(',');

                // Створюємо тип enum, якщо він ще не існує
                DB::statement(sprintf(
                    "DO $$ BEGIN
                                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = '%s') THEN
                                    CREATE TYPE %s AS ENUM (%s);
                                END IF;
                            END $$;",
                    $enumTypeName,
                    $enumTypeName,
                    $value
                ));

                // Додаємо стовпець з типом enum та nullable, якщо це необхідно
                $nullableClause = $nullable ? 'NULL' : 'NOT NULL';

                DB::statement(sprintf(
                    'ALTER TABLE "%s" ADD COLUMN "%s" %s %s;',
                    $this->getTable(),
                    $columnName,
                    $enumTypeName,
                    $nullableClause
                ));

                // Якщо задано значення за замовчуванням, додаємо його
                if ($default) {
                    DB::statement(sprintf(
                        'ALTER TABLE "%s" ALTER COLUMN "%s" SET DEFAULT %s;',
                        $this->getTable(),
                        $columnName,
                        "'{$default}'"
                    ));
                }
            });

    }
}
