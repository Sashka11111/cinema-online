<?php

namespace Liamtseva\Cinema\Http\Controllers\Socialite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Liamtseva\Cinema\Http\Controllers\Controller;
use Liamtseva\Cinema\Models\User;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if (! in_array($provider, ['google', 'telegram', 'discord', 'twitter', 'instagram', 'tiktok'])) {
            return redirect()->route('login')->with('error', 'Непідтримуваний провайдер');
        }

        $socialUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'provider_id' => $socialUser->id,
            'provider_name' => $provider,
        ], [
            'name' => $socialUser->name,
            'email' => $socialUser->email,
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
