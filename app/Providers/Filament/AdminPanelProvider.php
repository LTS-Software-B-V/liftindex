<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\FontProviders\SpatieGoogleFontProvider;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin; 
 

use Awcodes\LightSwitch\LightSwitchPlugin;
 
 
//use TomatoPHP\FilamentTenancy\FilamentTenancyAppPlugin;

use Filament\Widgets\StatsOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
           // ->font('Inter', provider: SpatieGoogleFontProvider::class)
            ->id('admin')
            ->path('admin')
          //  ->tenant(Company::class)
            ->login()
            ->unsavedChangesAlerts()
            ->colors([
                'primary' => Color::Amber,
            ])  
          //  ->plugin(FilamentTenancyAppPlugin::make())
           // ->plugin(FilamentTenancyAppPlugin::make())

           // ->tenantDomain('{tenant:slug}.localhost')
         ->readOnlyRelationManagersOnResourceViewPagesByDefault(false)
            ->brandLogo(fn() => view('components.logo'))
           // ->globalSearch(false)
            ->sidebarCollapsibleOnDesktop()
            ->darkMode(false)
            //   ->topNavigation()
            ->plugins([
                FilamentBackgroundsPlugin::make()->imageProvider(
                    MyImages::make()
                        ->directory('images/swisnl/filament-backgrounds/curated-by-swis'),
                     //   LightSwitchPlugin::make(),
                ),
            ])

         //   ->plugin(\TomatoPHP\FilamentPWA\FilamentPWAPlugin::make())
                
            ->plugins([FilamentFullCalendarPlugin::make()])
            ->maxContentWidth(MaxWidth::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([])
            ->widgets([



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
                SetTheme::class
             ])
            //->tenantMiddleware([

            //     SetTheme::class
            // ])
            ->authMiddleware([
                Authenticate::class,
            ])
            //   ->tenant(Company::class)
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->plugin(
                ThemesPlugin::make()
            );
    }
}
