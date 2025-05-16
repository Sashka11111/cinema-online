<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function mount()
    {
        // Перевіряємо, чи користувач авторизований
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Якщо пошта вже підтверджена, перенаправляємо на головну
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }
    }

    public function resend()
    {
        // Перевіряємо, чи користувач авторизований
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Перевіряємо, чи пошта вже підтверджена
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        // Відправляємо лист з підтвердженням
        Auth::user()->sendEmailVerificationNotification();

        // Встановлюємо повідомлення про успішну відправку
        session()->flash('message', 'Повідомлення для підтвердження електронної пошти надіслано!');

        return back();
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
