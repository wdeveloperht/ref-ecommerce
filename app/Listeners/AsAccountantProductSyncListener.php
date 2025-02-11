<?php

namespace App\Listeners;

use App\Events\AsAccountantProductSyncEvent;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Setting\Facades\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class AsAccountantProductSyncListener implements ShouldQueue
{
    public StoreProductService $storeProductService;

    /**
     * Create the event listener.
     */
    public function __construct(StoreProductService $storeProductService)
    {
        $this->storeProductService = $storeProductService;
    }

    /**
     * Handle the event.
     */
    public function handle(AsAccountantProductSyncEvent $event): void
    {
//        try {
            $product = $event->product;
            $request = new Request($event->request);
            $asAccountantId = $event->asAccountantId;

            $storeProduct = $this->storeProductService->execute($request, $product);
            //count === complite_count restet accountant_sync_product_last_id

            // if ($storeProduct instanceof Product) {
                Setting::get('accountant_sync_product_last_id', $asAccountantId);
            // }
            dump($asAccountantId, $storeProduct, $request->input());
//        } catch (\Exception|\Throwable $e) {
//            Log::error('ASACCOUNTANTPRODUCTSYNCLISTENER_FAIL', [
//                'message' => $e->getMessage(),
//                'trace' => $e->getTraceAsString()
//            ]);
//        }
    }
}
