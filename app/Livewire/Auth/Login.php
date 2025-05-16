<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email]);

            if (! $user->hasVerifiedEmail()) {
                // Тимчасовий вхід
                Auth::login($user, false); // Не запам'ятовуємо сесію
                $user->sendEmailVerificationNotification();
                session()->flash('message', 'Будь ласка, підтвердіть вашу електронну пошту. Лист надіслано!');

                return $this->redirectRoute('verification.notice', navigate: true);
            }

            Auth::login($user, $this->remember);
            session()->flash('status', 'Успішний вхід!');

            return $this->redirectRoute('home', navigate: true);
        }

        $this->addError('password', 'Невірний пароль.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
