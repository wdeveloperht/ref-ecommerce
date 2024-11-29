<?php

namespace Botble\Gallery\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Language\Facades\Language;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Models\GalleryItem;
use Botble\Gallery\Repositories\Eloquent\GalleryItemRepository;
use Botble\Gallery\Repositories\Eloquent\GalleryRepository;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryItemInterface;

class GalleryServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(GalleryInterface::class, function () {
            return new GalleryRepository(new Gallery());
        });

        $this->app->bind(GalleryItemInterface::class, function () {
            return new GalleryItemRepository(new GalleryItem());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/gallery')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-gallery')
                        ->priority(390)
                        ->name('plugins/gallery::gallery.menu')
                        ->icon('ti ti-slideshow')
                        ->route('gallery.index')
                );
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('gallery')
                    ->setTitle(trans('plugins/gallery::gallery.settings.title'))
                    ->withIcon('ti ti-slideshow')
                    ->withPriority(430)
                    ->withDescription(trans('plugins/gallery::gallery.settings.description'))
                    ->withRoute('gallery.settings')
            );
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule(Gallery::class);
        }

        $this->app->booted(function (): void {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
