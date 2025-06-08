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
            return $this->redirectRoute('login', navigate: true);
        }

        // Якщо пошта вже підтверджена, перенаправляємо на головну
        if (Auth::user()->hasVerifiedEmail()) {
            return $this->redirectRoute('home', navigate: true);
        }
    }

    public function resend()
    {
        // Перевіряємо, чи користувач авторизований
        if (! Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        // Перевіряємо, чи пошта вже підтверджена
        if (Auth::user()->hasVerifiedEmail()) {
            return $this->redirectRoute('home', navigate: true);
        }

        // Відправляємо лист з підтвердженням
        Auth::user()->sendEmailVerificationNotification();

        // Встановлюємо повідомлення про успішну відправку
        session()->flash('message', 'Повідомлення для підтвердження електронної пошти надіслано!');

        // Refresh the current page instead of using back()
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
