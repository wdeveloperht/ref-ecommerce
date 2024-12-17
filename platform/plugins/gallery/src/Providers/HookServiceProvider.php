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
                            ->label(trans('plugins/gallery::gallery.select_gallery'))
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
        $gallery = Gallery::query()
            ->wherePublished()
            ->where('key', $shortcode->key)
            ->first();

        if (empty($gallery) || $gallery->sliderItems->isEmpty()) {
            return null;
        }

        if (setting('gallery_using_assets', true) && defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $version = '1.0.0';
            $dist = asset('vendor/core/plugins/gallery');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'light-gallery-css',
                    $dist . '/libraries/light-gallery/lightgallery.css',
                    [],
                    [],
                    $version
                )
//                ->add('light-gallery-css', $dist . '/css/gallery.css', [], [], $version)
                ->add(
                    'lg-main-js',
                    $dist . '/libraries/light-gallery/lg-main.min.js',
                    ['jquery'],
                    [],
                    $version
                )
                ->add(
                    'lg-thumbnail-js',
                    $dist . '/libraries/light-gallery/lg-thumbnail.min.js',
                    ['jquery'],
                    [],
                    $version
                )
                ->add(
                    'lg-fullscreen-js',
                    $dist . '/libraries/light-gallery/lg-fullscreen.min.js',
                    ['jquery'],
                    [],
                    $version
                )
                ->add('gallery-js', $dist . '/js/gallery.js', ['jquery'], [], $version);
        }

        return view(apply_filters(GALLERY_VIEW_TEMPLATE, 'plugins/gallery::sliders'), [
            'sliders' => $gallery->sliderItems,
            'shortcode' => $shortcode,
            'slider' => $gallery,
        ]);
    }
}
