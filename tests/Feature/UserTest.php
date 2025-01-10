<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // Для скидання бази даних між тестами

    #[Test]
    public function a_user_can_be_created_using_the_factory()
    {
        // Створення користувача з певним гендером
        $user = User::factory()->gender(Gender::MALE)->create();

        // Перевірка, чи був користувач збережений у базі даних
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function a_user_has_a_hashed_password()
    {
        // Створення користувача за допомогою фабрики
        $user = User::factory()->create([
            'password' => 'my_password',
        ]);

        // Перевірка, чи пароль хешовано
        $this->assertTrue(Hash::check('my_password', $user->password));
    }

    #[Test]
    public function a_user_can_have_a_gender()
    {
        // Створення користувача з певним гендером
        $user = User::factory()->gender(Gender::MALE)->create();

        // Перевірка, чи гендер користувача є "male"
        $this->assertEquals(Gender::MALE, $user->gender);
    }

    #[Test]
    public function a_user_can_have_an_admin_role()
    {
        // Створення користувача з ролью admin
        $user = User::factory()->admin()->create();

        // Перевірка, чи роль користувача є "admin"
        $this->assertEquals('admin', $user->role);
    }
}
