<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Studio;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        Studio::factory(50)->create();
    }
}
