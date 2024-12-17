<?php

namespace Botble\Gallery\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GalleryRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(BaseStatusEnum::values()),
            'name' => ['required', 'string', 'max:250'],
            'key' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['string']
        ];
    }
}
