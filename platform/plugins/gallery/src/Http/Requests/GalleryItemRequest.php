<?php

namespace Botble\Gallery\Http\Requests;

use Botble\Support\Http\Requests\Request;

class GalleryItemRequest extends Request
{
    public function rules(): array
    {
        return [
            'gallery_id' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:0', 'max:1000'],
        ];
    }
}
