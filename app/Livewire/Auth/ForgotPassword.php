<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.required' => 'Поле Email є обов’язковим.',
        'email.email' => 'Введіть коректну email-адресу.',
        'email.exists' => 'Користувача з таким email не знайдено.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Посилання для скидання пароля надіслано на ваш email!');
            $this->email = ''; // Очищаємо поле після успіху
        } else {
            $this->addError('email', 'Не вдалося надіслати посилання. Перевірте email.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
