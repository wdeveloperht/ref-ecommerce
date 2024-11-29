<?php

namespace Botble\Gallery\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Botble\Gallery\Forms\Settings\GallerySettingForm;
use Botble\Gallery\Http\Requests\Settings\GallerySettingRequest;

class GallerySettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/gallery::gallery.settings.title'));

        return GallerySettingForm::create()->renderForm();
    }

    public function update(GallerySettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
