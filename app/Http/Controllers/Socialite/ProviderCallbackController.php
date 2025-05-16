<?php

namespace Liamtseva\Cinema\Http\Controllers\Socialite;

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
        if (! in_array($provider, ['google', 'telegram', 'discord'])) {
            return redirect()->route('login')->with('error', 'Непідтримуваний провайдер');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Помилка авторизації через соціальну мережу');
        }

        // Спочатку перевіряємо, чи існує користувач з таким provider_id
        $existingUserByProviderId = User::where('provider_id', $socialUser->id)
            ->where('provider_name', $provider)
            ->first();

        if ($existingUserByProviderId) {
            // Якщо користувач вже існує з цим provider_id, авторизуємо його
            Auth::login($existingUserByProviderId);

            return redirect('/')->with('status', 'Успішний вхід!');
        }

        // Перевіряємо, чи існує користувач з таким email
        if ($socialUser->email) {
            $existingUserByEmail = User::where('email', $socialUser->email)->first();

            if ($existingUserByEmail) {
                // Якщо користувач з таким email вже існує
                if ($existingUserByEmail->provider_name && $existingUserByEmail->provider_name !== $provider) {
                    // Зареєстрований через іншу соціальну мережу
                    return redirect()->route('login')->with('error',
                        "Цей email вже зареєстрований через {$existingUserByEmail->provider_name}. Будь ласка, використовуйте цей метод для входу.");
                } else {
                    // Зареєстрований через звичайну реєстрацію або іншим способом
                    // Оновлюємо дані користувача, щоб прив'язати його до цієї соціальної мережі
                    $existingUserByEmail->update([
                        'provider_id' => $socialUser->id,
                        'provider_name' => $provider,
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken,
                    ]);

                    Auth::login($existingUserByEmail);

                    return redirect('/')->with('status', 'Ваш обліковий запис успішно прив\'язано до '.ucfirst($provider).'!');
                }
            }
        }

        // Створюємо нового користувача
        $user = User::create([
            'name' => $socialUser->name ?? 'User_'.substr(md5($socialUser->id), 0, 8),
            'email' => $socialUser->email,
            'provider_id' => $socialUser->id,
            'provider_name' => $provider,
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
            'email_verified_at' => now(), // Вважаємо email підтвердженим, оскільки він підтверджений у соціальній мережі
        ]);

        Auth::login($user);

        return redirect('/')->with('status', 'Успішна реєстрація та вхід!');
    }
}
