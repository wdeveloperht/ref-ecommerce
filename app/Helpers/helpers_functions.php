<?php

use App\Contracts\ClientAPI\Client;

if (!function_exists('client')) {
    /**
     * @return \App\Contracts\ClientAPI\Client
     */
    function client() {
        return app(Client::class);
    }
}
