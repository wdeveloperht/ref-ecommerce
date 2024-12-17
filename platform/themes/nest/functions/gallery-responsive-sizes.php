<?php

use Botble\Gallery\Support\GallerySupport;

app()->booted(function (): void {
    if (is_plugin_active('gallery')) {
        GallerySupport::registerResponsiveImageSizes();
    }
});
