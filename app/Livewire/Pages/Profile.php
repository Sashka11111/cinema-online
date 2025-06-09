<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    // Загальна інформація
    public $name;
    public $email;
    public $avatar;
    public $newAvatar;
    public $backdrop;
    public $newBackdrop;
    public $description;
    public $birthday;
    public $gender;

    // Інформація про провайдера (тільки для читання)
    public $provider_name;
    public $email_verified_at;
    public $role;
    public $last_seen_at;

    // Налаштування користувача
    public $allow_adult;
    public $is_auto_next;
    public $is_auto_play;
    public $is_auto_skip_intro;
    public $is_private_favorites;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:users,name,' . Auth::id(),
            'email' => ['nullable', 'email', Rule::unique('users')->ignore(Auth::id())],
            'description' => 'nullable|string|min:3|max:248',
            'birthday' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|in:male,female,other',
            'newAvatar' => 'nullable|image|max:2048',
            'newBackdrop' => 'nullable|image|max:2048',
            'allow_adult' => 'boolean',
            'is_auto_next' => 'boolean',
            'is_auto_play' => 'boolean',
            'is_auto_skip_intro' => 'boolean',
            'is_private_favorites' => 'boolean',
        ];
    }

    protected function messages()
    {
        return [
            'birthday.before_or_equal' => 'Дата народження не може бути в майбутньому.',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->getRawOriginal('avatar');
        $this->backdrop = $user->getRawOriginal('backdrop');
        $this->description = $user->description;
        $this->birthday = $user->birthday?->format('Y-m-d');
        $this->gender = $user->gender?->value;

        // Інформація про провайдера (тільки для читання)
        $this->provider_name = $user->provider_name;
        $this->email_verified_at = $user->email_verified_at;
        $this->role = $user->role;
        $this->last_seen_at = $user->last_seen_at;

        // Налаштування користувача
        $this->allow_adult = $user->allow_adult ?? false;
        $this->is_auto_next = $user->is_auto_next ?? false;
        $this->is_auto_play = $user->is_auto_play ?? false;
        $this->is_auto_skip_intro = $user->is_auto_skip_intro ?? false;
        $this->is_private_favorites = $user->is_private_favorites ?? false;
    }

    public function updateProfile()
    {
        try {
            \Log::info('UpdateProfile called', [
                'newAvatar' => $this->newAvatar ? 'present' : 'null',
                'newBackdrop' => $this->newBackdrop ? 'present' : 'null'
            ]);

            $this->validate();

            $user = Auth::user();

            // Оновлюємо основні поля
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'description' => $this->description,
                'birthday' => $this->birthday ? \Carbon\Carbon::parse($this->birthday) : null,
                'gender' => $this->gender ? \Liamtseva\Cinema\Enums\Gender::from($this->gender) : null,
                'allow_adult' => $this->allow_adult,
                'is_auto_next' => $this->is_auto_next,
                'is_auto_play' => $this->is_auto_play,
                'is_auto_skip_intro' => $this->is_auto_skip_intro,
                'is_private_favorites' => $this->is_private_favorites,
            ]);

            // Обробляємо завантаження нового аватара
            if ($this->newAvatar) {
                \Log::info('Processing new avatar', ['file' => $this->newAvatar->getClientOriginalName()]);

                // Видаляємо старий аватар якщо він існує
                if ($user->getRawOriginal('avatar') && \Storage::disk('public')->exists($user->getRawOriginal('avatar'))) {
                    \Storage::disk('public')->delete($user->getRawOriginal('avatar'));
                    \Log::info('Deleted old avatar', ['path' => $user->getRawOriginal('avatar')]);
                }

                $path = $this->newAvatar->store('avatars', 'public');
                \Log::info('Stored new avatar', ['path' => $path]);

                $user->update(['avatar' => $path]);
                $this->avatar = $path;
                $this->newAvatar = null; // Скидаємо тимчасовий файл
            }

            // Обробляємо завантаження нового фону
            if ($this->newBackdrop) {
                \Log::info('Processing new backdrop', ['file' => $this->newBackdrop->getClientOriginalName()]);

                // Видаляємо старий фон якщо він існує
                if ($user->getRawOriginal('backdrop') && \Storage::disk('public')->exists($user->getRawOriginal('backdrop'))) {
                    \Storage::disk('public')->delete($user->getRawOriginal('backdrop'));
                    \Log::info('Deleted old backdrop', ['path' => $user->getRawOriginal('backdrop')]);
                }

                $path = $this->newBackdrop->store('backdrops', 'public');
                \Log::info('Stored new backdrop', ['path' => $path]);

                $user->update(['backdrop' => $path]);
                $this->backdrop = $path;
                $this->newBackdrop = null; // Скидаємо тимчасовий файл
            }

            $this->dispatch('profile-updated');
            session()->flash('message', 'Профіль успішно оновлено');

        } catch (\Exception $e) {
            session()->flash('error', 'Помилка при оновленні профілю: ' . $e->getMessage());
        }
    }

    public function updatedNewAvatar()
    {
        $this->validateOnly('newAvatar');
    }

    public function updatedNewBackdrop()
    {
        $this->validateOnly('newBackdrop');
    }

    public function render()
    {
        return view('livewire.pages.profile');
    }
}
