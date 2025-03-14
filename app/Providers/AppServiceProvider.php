<?php

namespace Liamtseva\Cinema\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        TextInput::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('heroicon-o-information-circle'));
        DatePicker::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('clarity-date-line'));
        Select::configureUsing(fn ($component) => $component->prefixIconColor('primary'));
        DateTimePicker::configureUsing(fn ($component) => $component->prefixIconColor('primary')->prefixIcon('clarity-date-line'));
        Section::configureUsing(fn ($component) => $component->iconColor('primary'));
        Model::unguard();
        Model::shouldBeStrict();
    }
}
