<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Gallery\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function (): void {
            Route::resource('', 'GalleryController')->parameters(['' => 'gallery']);

            Route::post('sorting', [
                'as' => 'sorting',
                'uses' => 'GallerySettingController@postSorting',
                'permission' => 'gallery.edit',
            ]);
        });

        Route::group(['prefix' => 'gallery-items', 'as' => 'gallery-item.'], function (): void {
            Route::resource('', 'GallerySettingController')->except([
                'index',
            ])->parameters(['' => 'gallery-item']);

            Route::match(['GET', 'POST'], 'list/{id}', [
                'as' => 'index',
                'uses' => 'GallerySettingController@index',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'settings', 'as' => 'gallery.'], function (): void {
            Route::get('gallery', [
                'as' => 'settings',
                'uses' => 'Settings\GallerySettingController@edit',
            ]);

            Route::put('gallery', [
                'as' => 'settings.update',
                'uses' => 'Settings\GallerySettingController@update',
                'permission' => 'gallery.settings',
            ]);
        });
    });
});
