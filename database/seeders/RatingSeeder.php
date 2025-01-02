<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Rating;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        Rating::factory(20)->create();
    }
}
