<?php

namespace Botble\Services\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ServiceInterface extends RepositoryInterface
{
    public function getAll(): Collection;
}
