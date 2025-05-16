<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class SocialLoginComponent extends Component
{
    /**
     * Перенаправляє на сторінку авторизації через соціальну мережу
     *
     * @param  string  $provider  Назва провайдера (google, telegram, discord)
     * @return void
     */
    public function redirectToProvider(string $provider)
    {
        if (! in_array($provider, ['google', 'telegram', 'discord'])) {
            session()->flash('error', 'Непідтримуваний провайдер');

            return;
        }

        return redirect()->route('auth.social.redirect', ['provider' => $provider]);
    }

    public function render()
    {
        return view('livewire.components.social-login-component');
    }
}
