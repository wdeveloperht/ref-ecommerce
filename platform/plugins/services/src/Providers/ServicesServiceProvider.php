<?php

namespace Botble\Services\Providers;

use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Services\Facades\ServiceManager;
use Botble\Services\Forms\ServiceForm;
use Botble\Services\Models\Service;
use Botble\Services\Repositories\Eloquent\ServiceRepository;
use Botble\Services\Repositories\Interfaces\ServiceInterface;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(ServiceInterface::class, function () {
            return new ServiceRepository(new Service());
        });

        AliasLoader::getInstance()->alias('ServiceManager', ServiceManager::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/services')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadHelpers()
            ->loadAndPublishViews();

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-services',
                    'priority' => 8,
                    'icon' => 'ti ti-settings',
                    'name' => 'plugins/services::services.name',
                    'permissions' => ['services.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-services-list',
                    'parent_id' => 'cms-plugins-services',
                    'priority' => 1,
                    'name' => 'plugins/services::services.name',
                    'url' => fn() => route('services.index'),
                    'permissions' => ['services.index'],
                ]);
        });

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (class_exists(Shortcode::class)) {
                Shortcode::register('services', __('Services'), __('Services'), function ($shortcode) {
                    if (!$shortcode->key) {
                        return null;
                    }

                    return ServiceManager::displayService((string)$shortcode->key);
                });

                Shortcode::setAdminConfig('services', function ($attributes) {
                    $service = Service::query()
                        ->wherePublished()
                        ->pluck('name', 'key')
                        ->all();

                    return ShortcodeForm::createFromArray($attributes)
                        ->withLazyLoading()
                        ->add(
                            'key',
                            SelectField::class,
                            SelectFieldOption::make()
                                ->label(trans('plugins/services::services.select_service'))
                                ->choices($service)
                        );
                });
            }
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Service::class, [
                'name',
                'subtitle',
                'description',
                'slug'
            ]);
        }

        ServiceForm::beforeRendering(function (): void {
            add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, function ($request, $data = null) {
                if (!$data instanceof Service || !in_array(Route::currentRouteName(), ['services.create', 'services.edit'])) {
                    return false;
                }

                return true;
            }, 45, 2);
        });
    }
}
