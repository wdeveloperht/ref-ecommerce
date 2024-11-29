<?php

namespace Botble\Gallery\Providers;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Supports\ServiceProvider;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Gallery\Models\Gallery;
use Botble\Theme\Facades\Theme;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (function_exists('shortcode')) {
            add_shortcode(
                'gallery',
                trans('plugins/gallery::gallery.gallery_shortcode_name'),
                trans('plugins/gallery::gallery.gallery_shortcode_description'),
                [$this, 'render']
            );

            shortcode()->setPreviewImage(
                'gallery',
                asset('vendor/core/plugins/gallery/images/ui-blocks/gallery.png')
            );

            shortcode()->setAdminConfig('gallery', function (array $attributes) {
                return ShortcodeForm::createFromArray($attributes)
                    ->add(
                        'key',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/gallery::gallery.select_slider'))
                            ->choices(Gallery::query()
                                ->wherePublished()
                                ->pluck('name', 'key')
                                ->all())
                    );
            });
        }
    }

    public function render(Shortcode $shortcode): View|Factory|Application|null
    {
        $slider = Gallery::query()
            ->wherePublished()
            ->where('key', $shortcode->key)
            ->first();

        if (empty($slider) || $slider->sliderItems->isEmpty()) {
            return null;
        }

        if (setting('simple_slider_using_assets', true) && defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $version = '1.0.2';
            $dist = asset('vendor/core/plugins/gallery');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'gallery-owl-carousel-css',
                    $dist . '/libraries/owl-carousel/owl.carousel.css',
                    [],
                    [],
                    $version
                )
                ->add('gallery-css', $dist . '/css/gallery.css', [], [], $version)
                ->add(
                    'gallery-owl-carousel-js',
                    $dist . '/libraries/owl-carousel/owl.carousel.js',
                    ['jquery'],
                    [],
                    $version
                )
                ->add('gallery-js', $dist . '/js/gallery.js', ['jquery'], [], $version);
        }

        return view(apply_filters(SIMPLE_SLIDER_VIEW_TEMPLATE, 'plugins/gallery::sliders'), [
            'sliders' => $slider->sliderItems,
            'shortcode' => $shortcode,
            'slider' => $slider,
        ]);
    }
}
