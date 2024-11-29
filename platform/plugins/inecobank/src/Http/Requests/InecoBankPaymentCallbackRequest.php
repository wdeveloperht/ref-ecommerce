<?php

namespace Botble\InecoBank\Http\Requests;

use Botble\Support\Http\Requests\Request;

class InecoBankPaymentCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'size:66'],
        ];
    }
}
