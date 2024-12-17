<?php

namespace Botble\Gallery;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('gallery_items');

        Setting::delete([
            'gallery_using_assets',
        ]);
    }
}
