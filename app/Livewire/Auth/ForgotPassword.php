<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Liamtseva\Cinema\Models\User;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Перевіряємо, чи підтверджена пошта
        $user = User::where('email', $this->email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            session()->flash('message', 'Ваша пошта не підтверджена. Ми надіслали вам лист для підтвердження.');

            return $this->redirectRoute('verification.notice', navigate: true);
        }

        // Відправляємо посилання для скидання пароля
        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Посилання для скидання пароля надіслано на вашу електронну пошту!');
            $this->reset('email'); // Очищаємо поле після успіху
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
