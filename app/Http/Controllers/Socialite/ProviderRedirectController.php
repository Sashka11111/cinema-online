<?php

namespace Liamtseva\Cinema\Http\Controllers\Socialite;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Liamtseva\Cinema\Http\Controllers\Controller;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if (! in_array($provider, ['google', 'telegram', 'discord'])) {
            return redirect()->route('login')->with('error', 'Непідтримуваний провайдер');
        }

        try {

            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {

            return redirect()->route('login')->with('error', 'Помилка при авторизації');
        }
    }
}
