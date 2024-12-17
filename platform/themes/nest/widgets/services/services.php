<?php

use Botble\Widget\AbstractWidget;

class ServicesWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Services'),
            'description' => __('Display services on sidebar'),
            'services_key' => 0,
        ]);
    }
}
