<?php

namespace App\Http\Controllers;

use App\Constants\AsAccountant;
use App\Constants\ClientResponse;
use App\Events\AsAccountantProductSyncEvent;
use App\Infrastructure\Services\AsAccountantService;
use App\Jobs\AsAccountantProductSyncJob;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Setting\Facades\Setting;
use Botble\Slug\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AsAccountantController extends Controller
{
    /**
     * @param AsAccountantService $asAccountantService
     * @return void
     */
    public function syncProducts(AsAccountantService $asAccountantService): void
    {
        $response = $asAccountantService->getProducts();
        if ($response['status'] == ClientResponse::STATUS['success']) {
//            $startKey = Setting::get('accountant_sync_product_last_id');
//            $asAccountantService->fetchProducts($response['data'], $startKey);
            foreach ($response['data'] as $row) {
                AsAccountantProductSyncJob::dispatch($row)->onQueue('high');
                // event(new AsAccountantProductSyncEvent($product, $this->setProductRequest($product, $row)));
                // dispatch(new AsAccountantProductSyncJob($row))->onQueue('high');
            }
        }
    }
}
