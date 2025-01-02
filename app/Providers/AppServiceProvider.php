<?php

namespace Liamtseva\Cinema\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }
}
