<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Person;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        Person::factory(100)->create();
    }
}
