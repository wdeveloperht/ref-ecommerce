<?php

namespace Botble\Services\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ServiceRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'key' => 'required|max:120|unique:services,key,' . $this->route('service.id'),
            'order' => ['required', 'integer', 'min:0', 'max:127'],
            'status' => Rule::in(BaseStatusEnum::values())
        ];
    }
}
