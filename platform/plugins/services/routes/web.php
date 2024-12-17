<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Services\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'services', 'as' => 'services.'], function (): void {
            Route::resource('', 'ServiceController')->parameters(['' => 'service']);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function (): void {
            Route::get('services-click/{key}', [
                'as' => 'public.services-click',
                'uses' => 'PublicController@getservicesClick',
            ]);

            Route::get('ac-{randomHash}/{servicesKey}', [
                'as' => 'public.services-click.alternative',
                'uses' => 'PublicController@getservicesClickAlternative',
            ]);

            Route::get('ac-{randomHash}/{servicesKey}/{size}/{hashName}.jpg', [
                'as' => 'public.services-click.image',
                'uses' => 'PublicController@getservicesImage',
            ]);
        });
    }
});
