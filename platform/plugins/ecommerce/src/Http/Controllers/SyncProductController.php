<?php

namespace Botble\Ecommerce\Http\Controllers;

use App\Events\AsAccountantProductSync;
use Botble\DataSynchronize\Syncer\Syncer;
use Botble\DataSynchronize\Http\Controllers\SyncController;
use Botble\Ecommerce\Syncers\ProductSyncer;
use Throwable;

class SyncProductController extends SyncController
{
    protected function getSyncer(): Syncer
    {
        return ProductSyncer::make();
    }

    public function index()
    {
        $this->pageTitle($this->getSyncer()->getHeading());

        return $this->getSyncer()->render();
    }

    public function store()
    {
        event(new AsAccountantProductSync());

        return $this
            ->httpResponse()
//            ->setData(['<p>dasdasdadasdad</p>'])
//            ->withCreatedSuccessMessage();
            ->setMessage('Product Sync Started');

//        try {
//            event(new AsAccountantProductSync());
//
//            return $this
//                ->httpResponse()
//                ->setData(['Product Sync Started'])
//                ->setCode(200)
//                ->setMessage('Product Sync Started');
//        } catch (Throwable $e) {
//            BaseHelper::logError($e);
//
//            return $this
//                ->httpResponse()
//                ->setError()
//                ->setCode(400)
//                ->setMessage($e->getMessage());
//        }
    }

}
