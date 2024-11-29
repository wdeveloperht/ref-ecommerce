<?php

namespace Botble\InecoBank;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_name',
            'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_description',
            'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_payment_mode',
            'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_user_name',
            'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_password'
        ]);

//        Setting::delete([
//            'payment_inecobank_name',
//            'payment_inecobank_description',
//            'payment_inecobank_payment_type',
//            'payment_inecobank_client_id',
//            'payment_inecobank_secret',
//            'payment_inecobank_status',
//        ]);
    }
}
