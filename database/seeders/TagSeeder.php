<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::factory(1_000)->create();
    }
}
