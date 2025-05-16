<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Models\User;
use Livewire\Component;

class ResetPassword extends Component
{
    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $token = '';

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ];

    public function mount($token, Request $request)
    {
        $this->token = $token;
        $this->email = $request->query('email', ''); // Отримуємо email із запиту
    }

    public function performReset()
    {
        $this->validate();

        // Перевіряємо, чи підтверджена пошта
        $user = User::where('email', $this->email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            session()->flash('message', 'Ваша пошта не підтверджена. Ми надіслали вам лист для підтвердження.');
            \Log::info('Redirecting to verification.notice from ResetPassword', ['email' => $this->email]);

            return $this->redirectRoute('verification.notice', navigate: true);
        }

        // Скидаємо пароль
        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', 'Пароль успішно змінено! Тепер ви можете увійти.');

            return $this->redirectRoute('login', navigate: true);
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
