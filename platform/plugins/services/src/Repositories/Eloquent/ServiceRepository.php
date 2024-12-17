<?php

namespace Botble\Services\Repositories\Eloquent;

use Botble\Services\Repositories\Interfaces\ServiceInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository extends RepositoriesAbstract implements ServiceInterface
{
    public function getAll(): Collection
    {
        // @phpstan-ignore-next-line
        $data = $this->model
            ->wherePublished()
//            ->notExpired()
            ->with(['metadata']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
