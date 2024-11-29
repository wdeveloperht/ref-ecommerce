<?php

namespace Botble\Gallery\Forms\Settings;

use Botble\Setting\Forms\SettingForm;
use Botble\Gallery\Http\Requests\Settings\GallerySettingRequest;

class GallerySettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/gallery::gallery.settings.title'))
            ->setSectionDescription(trans('plugins/gallery::gallery.settings.description'))
            ->setValidatorClass(GallerySettingRequest::class)
            ->add('gallery_using_assets', 'onOffCheckbox', [
                'label' => trans('plugins/gallery::gallery.settings.using_assets'),
                'value' => setting('gallery_using_assets', true),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
            ])
            ->add('gallery_assets', 'html', [
                'html' => view('plugins/gallery::partials.gallery-asset-fields')->render(),
            ]);
    }
}
