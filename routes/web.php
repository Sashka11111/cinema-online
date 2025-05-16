<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Liamtseva\Cinema\Http\Controllers\Socialite\ProviderCallbackController;
use Liamtseva\Cinema\Http\Controllers\Socialite\ProviderRedirectController;
use Liamtseva\Cinema\Livewire\Actions\Logout;
use Liamtseva\Cinema\Livewire\Auth\ForgotPassword;
use Liamtseva\Cinema\Livewire\Auth\Login;
use Liamtseva\Cinema\Livewire\Auth\Register;
use Liamtseva\Cinema\Livewire\Auth\ResetPassword;
use Liamtseva\Cinema\Livewire\Auth\VerifyEmail;
use Liamtseva\Cinema\Livewire\Pages\Home;
use Liamtseva\Cinema\Livewire\Pages\MovieShow;
use Liamtseva\Cinema\Livewire\Pages\MoviesPage;

Route::get('/', Home::class)->name('home');

// Додаємо маршрут для сторінки фільмів
Route::get('/movies', MoviesPage::class)->name('movies');

// Додаємо маршрут для перегляду окремого фільму
Route::get('/movies/{movie}', MovieShow::class)->name('movies.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
    Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', Logout::class)->name('logout');
    Route::get('/email/verify', VerifyEmail::class)
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    })->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
});
