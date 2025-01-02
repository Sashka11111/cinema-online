<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(20)->create();
        User::factory()->state(['role'=> Role::ADMIN->value, 'email'=>'admin@gmail.com'])->create();
    }
}
