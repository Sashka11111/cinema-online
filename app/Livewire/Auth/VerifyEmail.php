<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Livewire\Component;

class VerifyEmail extends Component
{
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        auth()->user()->sendEmailVerificationNotification();

        session()->flash('message', 'Verification link sent!');

        return back();
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
