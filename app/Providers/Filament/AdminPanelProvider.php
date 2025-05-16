<?php

namespace Liamtseva\Cinema\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Nuxtifyts\DashStackTheme\DashStackThemePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->brandName('Cinema')
            ->colors([
                'primary' => '#93b3f4',
            ])
//            ->plugins([
//                DashStackThemePlugin::make(),
//            ])
            ->font('DM Sans')
            ->brandLogo(asset('images/icon.svg'))
            ->favicon(asset('images/icon.svg'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'Liamtseva\\Cinema\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'Liamtseva\Cinema\\Filament\\Admin\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'Liamtseva\\Cinema\\Filament\\Admin\\Widgets')
            ->widgets([])
            ->navigationGroups([
                'Контент',
                'Персони та студії',
                'Користувацька активність',
                'Коментарі',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
