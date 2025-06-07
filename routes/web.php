<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Liamtseva\Cinema\Http\Controllers\Socialite\ProviderCallbackController;
use Liamtseva\Cinema\Http\Controllers\Socialite\ProviderRedirectController;
use Liamtseva\Cinema\Livewire\Actions\Logout;
use Liamtseva\Cinema\Livewire\Auth\ForgotPassword;
use Liamtseva\Cinema\Livewire\Auth\Login;
use Liamtseva\Cinema\Livewire\Auth\Register;
use Liamtseva\Cinema\Livewire\Auth\ResetPassword;
use Liamtseva\Cinema\Livewire\Auth\VerifyEmail;
use Liamtseva\Cinema\Livewire\Pages\CookiePolicy;
use Liamtseva\Cinema\Livewire\Pages\Home;
use Liamtseva\Cinema\Livewire\Pages\MovieCommentsPage;
use Liamtseva\Cinema\Livewire\Pages\MovieShow;
use Liamtseva\Cinema\Livewire\Pages\MoviesPage;
use Liamtseva\Cinema\Livewire\Pages\MovieWatchPage;
use Liamtseva\Cinema\Livewire\Pages\PersonDetail;
use Liamtseva\Cinema\Livewire\Pages\PrivacyPolicy;
use Liamtseva\Cinema\Livewire\Pages\Profile;
use Liamtseva\Cinema\Livewire\Pages\RoomWatchPage;
use Liamtseva\Cinema\Livewire\Pages\SelectionShowPage;
use Liamtseva\Cinema\Livewire\Pages\SelectionsPage;
use Liamtseva\Cinema\Livewire\Pages\TermsOfUse;
use Liamtseva\Cinema\Livewire\Pages\UserListsPage;

Route::get('/', Home::class)->name('home');

// Універсальний маршрут для різних типів контенту
Route::get('/movies', MoviesPage::class)->name('movies');
Route::get('/series', MoviesPage::class)->defaults('contentType', 'series')->name('series');
Route::get('/cartoons', MoviesPage::class)->defaults('contentType', 'cartoons')->name('cartoons');
Route::get('/cartoon-series', MoviesPage::class)->defaults('contentType', 'cartoon_series')->name('cartoon-series');
Route::get('/anime', MoviesPage::class)->defaults('contentType', 'anime')->name('anime');

Route::get('/selections', SelectionsPage::class)->name('selections');
Route::get('/selection/{slug}', SelectionShowPage::class)->name('selection.show');
Route::get('/person/{person}', PersonDetail::class)->name('person.show');

Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy-policy');
Route::get('/terms-of-use', TermsOfUse::class)->name('terms-of-use');
Route::get('/cookie-policy', CookiePolicy::class)->name('cookie-policy');
// Додаємо маршрут для перегляду окремого фільму
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
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/my-lists', UserListsPage::class)->name('user-lists');

    // Broadcasting authorization
    Broadcast::routes(['middleware' => ['web', 'auth']]);
});

Route::get('/movies/{movie}', MovieShow::class)->name('movies.show');
Route::get('/movies/{movie}/comments', MovieCommentsPage::class)->name('movies.comments');
Route::get('/movies/{movie}/watch/', MovieWatchPage::class)->name('movies.watch');

Route::get('/movies/{movie}/watch/{episodeNumber}', MovieWatchPage::class)->name('movies.watch.episode');
// нову сторінку
Route::get('/movies/{movie}/watch/{episodeNumber}/{room}', RoomWatchPage::class)->name('movies.watch.room');
