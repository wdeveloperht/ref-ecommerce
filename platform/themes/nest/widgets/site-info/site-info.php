<?php

use Botble\Widget\AbstractWidget;

class SiteInfoWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Site information'),
            'description' => __('Widget display site information'),
            'show_logo' => null,
            'about' => null,
            'address' => null,
            'phone' => null,
            'email' => null,
            'working_hours' => null,
        ]);
    }
}
