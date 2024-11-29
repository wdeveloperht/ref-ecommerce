<?php

namespace Botble\Gallery\Support;

use Botble\Gallery\Forms\GalleryItemForm;

class GallerySupport
{
    public static function registerResponsiveImageSizes(): void
    {
        GalleryItemForm::extend(function (GalleryItemForm $form) {
            $form
                ->addAfter('image', 'tablet_image', 'mediaImage', [
                    'label' => __('Tablet Image'),
                    'help_block' => [
                        'text' => __(
                            'For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'
                        ),
                    ],
                    'metadata' => true,
                ])
                ->addAfter('tablet_image', 'mobile_image', 'mediaImage', [
                    'label' => __('Mobile Image'),
                    'help_block' => [
                        'text' => __(
                            'For devices with width less than 768px, if empty, will use the image from the tablet.'
                        ),
                    ],
                    'metadata' => true,
                ]);

            return $form;
        }, 127);
    }
}
