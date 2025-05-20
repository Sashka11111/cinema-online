<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    // Загальна інформація
    public $name;

    public $email;

    public $profilePhoto;

    public $newProfilePhoto;

    // Зміна пароля
    public $currentPassword;

    public $newPassword;

    public $newPasswordConfirmation;

    // Налаштування
    public $emailNotifications = true;

    public $pushNotifications = true;

    public $privateProfile = false;

    public $showWatchHistory = true;

    // Статистика
    public $watchedMovies = 0;

    public $watchedSeries = 0;

    public $totalWatchTime = 0;

    public $favoriteGenres = [];

    //    protected $rules = [
    //        'name' => 'required|string|max:255',
    //        'email' => 'required|email|max:255',
    //        'bio' => 'nullable|string|max:500',
    //        'newProfilePhoto' => 'nullable|image|max:1024',
    //
    //        'currentPassword' => 'nullable|required_with:newPassword',
    //        'newPassword' => ['nullable', 'confirmed', Password::defaults()],
    //
    //        'emailNotifications' => 'boolean',
    //        'pushNotifications' => 'boolean',
    //        'privateProfile' => 'boolean',
    //        'showWatchHistory' => 'boolean',
    //    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        //        $this->profilePhoto = $user->profile_photo_url;

        // Завантаження налаштувань користувача
        $this->emailNotifications = $user->settings['email_notifications'] ?? true;
        $this->pushNotifications = $user->settings['push_notifications'] ?? true;
        $this->privateProfile = $user->settings['private_profile'] ?? false;
        $this->showWatchHistory = $user->settings['show_watch_history'] ?? true;

        // Завантаження статистики
        $this->loadUserStatistics();
    }

    protected function loadUserStatistics()
    {
        $user = Auth::user();

        $this->watchedMovies = $user->watchedMovies()->count();
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],

            'newProfilePhoto' => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->newProfilePhoto) {
            $path = $this->newProfilePhoto->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $this->profilePhoto = Storage::url($path);
        }

        $user->save();

        $this->dispatch('profile-updated');
        session()->flash('message', 'Профіль успішно оновлено');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Поточний пароль невірний');

            return;
        }

        $user->password = Hash::make($this->newPassword);
        $user->save();

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

        session()->flash('password_message', 'Пароль успішно змінено');
    }

    public function updateSettings()
    {
        $this->validate([
            'emailNotifications' => 'boolean',
            'pushNotifications' => 'boolean',
            'privateProfile' => 'boolean',
            'showWatchHistory' => 'boolean',
        ]);

        $user = Auth::user();
        $user->settings = [
            'email_notifications' => $this->emailNotifications,
            'push_notifications' => $this->pushNotifications,
            'private_profile' => $this->privateProfile,
            'show_watch_history' => $this->showWatchHistory,
        ];

        $user->save();

        session()->flash('settings_message', 'Налаштування успішно оновлено');
    }

    public function render()
    {
        return view('livewire.pages.profile');
    }
}
