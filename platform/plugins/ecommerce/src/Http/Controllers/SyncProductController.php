<?php

namespace Botble\Ecommerce\Http\Controllers;

use App\Constants\ClientResponse;
use App\Events\AsAccountantProductSyncEvent;
use App\Infrastructure\Services\AsAccountantService;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Syncers\Syncer;
use Botble\Ecommerce\Syncers\Controllers\SyncController;
use Illuminate\Http\Request;
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

    public function store(Request $request, AsAccountantService $asAccountantService)
    {
        dd($request->all());

        $response = $asAccountantService->getProducts();
        if ($response['status'] == ClientResponse::STATUS['success']) {
            foreach ($response['data'] as $row) {
                $sku = $row['MTCode'];
                $product = Product::where('sku', $sku)->first() ??  new Product();
                event(new AsAccountantProductSyncEvent($product, $this->setProductRequest($product, $row)));
            }
        }
        dd(28);
        // event(new AsAccountantProductSyncEvent());

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
