<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSeeLivewire('pages.profile');
    }

    /** @test */
    public function user_can_update_basic_profile_information()
    {
        $user = User::factory()->create([
            'name' => 'OldName',
            'email' => 'old@example.com',
            'description' => 'Old description',
        ]);

        $this->actingAs($user);

        Livewire::test('pages.profile')
            ->set('name', 'NewName')
            ->set('email', 'new@example.com')
            ->set('description', 'New description')
            ->set('birthday', '1990-01-01')
            ->set('gender', 'male')
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->assertSessionHas('message', 'Профіль успішно оновлено');

        $user->refresh();
        $this->assertEquals('NewName', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('New description', $user->description);
        $this->assertEquals('1990-01-01', $user->birthday->format('Y-m-d'));
        $this->assertEquals(Gender::MALE, $user->gender);
    }

    /** @test */
    public function user_can_update_settings()
    {
        $user = User::factory()->create([
            'allow_adult' => false,
            'is_auto_play' => false,
            'is_auto_next' => false,
            'is_auto_skip_intro' => false,
            'is_private_favorites' => false,
        ]);

        $this->actingAs($user);

        Livewire::test('pages.profile')
            ->set('allow_adult', true)
            ->set('is_auto_play', true)
            ->set('is_auto_next', true)
            ->set('is_auto_skip_intro', true)
            ->set('is_private_favorites', true)
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->assertSessionHas('message', 'Профіль успішно оновлено');

        $user->refresh();
        $this->assertTrue($user->allow_adult);
        $this->assertTrue($user->is_auto_play);
        $this->assertTrue($user->is_auto_next);
        $this->assertTrue($user->is_auto_skip_intro);
        $this->assertTrue($user->is_private_favorites);
    }

    /** @test */
    public function user_can_upload_avatar()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        Livewire::test('pages.profile')
            ->set('newAvatar', $file)
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->assertSessionHas('message', 'Профіль успішно оновлено');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function validation_works_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('pages.profile')
            ->set('name', '') // Required field
            ->set('email', 'invalid-email') // Invalid email
            ->set('birthday', '2030-01-01') // Future date
            ->set('gender', 'invalid') // Invalid gender
            ->call('updateProfile')
            ->assertHasErrors(['name', 'email', 'birthday', 'gender']);
    }

    /** @test */
    public function name_must_be_unique()
    {
        $existingUser = User::factory()->create(['name' => 'ExistingName']);
        $user = User::factory()->create(['name' => 'CurrentName']);
        
        $this->actingAs($user);

        Livewire::test('pages.profile')
            ->set('name', 'ExistingName')
            ->call('updateProfile')
            ->assertHasErrors(['name']);
    }

    /** @test */
    public function user_can_keep_same_name()
    {
        $user = User::factory()->create(['name' => 'SameName']);
        $this->actingAs($user);

        Livewire::test('pages.profile')
            ->set('name', 'SameName')
            ->call('updateProfile')
            ->assertHasNoErrors(['name']);
    }
}
