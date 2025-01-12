<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(20)->create();
        User::factory()->state(['email' => 'admin@gmail.com'])->admin()->create();
    }
}
