<?php

use Botble\Gallery\Models\Gallery;
use Illuminate\Database\Eloquent\Collection;

if (! function_exists('get_all_gallery')) {
    /**
     * @deprecated
     */
    function get_all_gallery(array $condition = []): Collection
    {
        return Gallery::query()->where($condition)->get();
    }
}
