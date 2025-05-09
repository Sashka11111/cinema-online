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

Route::view('/', 'livewire.pages.home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('auth.forgot-password');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('auth.reset-password');
    Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
    Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', Logout::class)->name('logout');
    Route::get('/email/verify', function () {
        return view('livewire.auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/home');
    })->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
});
