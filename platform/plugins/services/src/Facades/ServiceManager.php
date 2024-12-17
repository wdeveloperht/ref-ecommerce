<?php

namespace Botble\Services\Facades;

use Botble\Services\Supports\ServiceManager as ServiceManagerSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string display(string $location, array $attributes = [])
 * @method static \Botble\Services\Supports\ServiceManager load(bool $force = false, array $with = [])
 * @method static bool locationHasService(string $location)
 * @method static string|null displayService(string|null $key, array $attributes = [])
 * @method static \Illuminate\Support\Collection getData(bool $isLoad = false, bool $isNotExpired = false)
 * @method static \Botble\Services\Supports\ServiceManager registerLocation(string $key, string $name)
 * @method static array getLocations()
 * @method static \Botble\Services\Models\Service|null getService(string $key)
 *
 * @see \Botble\Services\Supports\ServiceManager
 */
class ServiceManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ServiceManagerSupport::class;
    }
}
