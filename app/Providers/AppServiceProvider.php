<?php

namespace Liamtseva\Cinema\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Liamtseva\Cinema\Http\Responses\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    public function boot(): void
    {
        TextInput::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('heroicon-o-information-circle'));
        DatePicker::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('clarity-date-line'));
        Select::configureUsing(fn ($component) => $component->prefixIconColor('primary'));
        DateTimePicker::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('clarity-date-line'));
        Section::configureUsing(fn ($component) => $component->iconColor('primary'));
        Toggle::configureUsing(function (Toggle $component): void {
            $component
                ->onIcon('heroicon-o-check-circle')  // Іконка для стану "увімкнено" (true)
                ->offIcon('heroicon-o-x-circle')     // Іконка для стану "вимкнено" (false)
                ->onColor('primary')                 // Колір для стану "увімкнено"
                ->offColor('gray');                // Колір для стану "вимкнено"
        });
        Model::unguard();
        Model::shouldBeStrict();
    }
}
