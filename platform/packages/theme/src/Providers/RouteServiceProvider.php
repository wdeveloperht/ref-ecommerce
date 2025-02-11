<?php

namespace Botble\Theme\Providers;

use Botble\Theme\Facades\Theme;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Move base routes to a service provider to make sure all filters & actions can hook to base routes
     */
    public function boot(): void
    {
        $this->app->booted(function (): void {
            $this->loadRoutesFromTheme(Theme::getThemeName());

            if (Theme::hasInheritTheme()) {
                $this->loadRoutesFromTheme(Theme::getInheritTheme());
            }
        });

//        $this->routes(function () {
//            Route::middleware('api')
//                ->prefix('api')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/api.php'));
//
//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web.php'));
//        });
    }

    protected function loadRoutesFromTheme(string $theme): void
    {
        $routeFilePath = theme_path($theme . '/routes/web.php');

        if ($routeFilePath && $this->app['files']->exists($routeFilePath)) {
            $this->loadRoutesFrom($routeFilePath);
        }
    }
}
