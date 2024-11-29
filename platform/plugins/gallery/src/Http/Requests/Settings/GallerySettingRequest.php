<?php

namespace Botble\Gallery\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class GallerySettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'simple_slider_using_assets' => new OnOffRule(),
        ];
    }
}
