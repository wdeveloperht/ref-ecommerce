<?php

namespace App\Jobs;

use App\Constants\Logging;
use Botble\Ecommerce\Models\Product;
use App\Infrastructure\Services\AsAccountantService;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AsAccountantProductSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public int $retryAfter = 600;

    public int $timeout = 1000000000;

    private array $data;


    public AsAccountantService $accountantService;
    public StoreProductService $storeProductService;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->accountantService = new AsAccountantService();
        $this->storeProductService = new StoreProductService();
    }

    public function handle(): void
    {
        try {
            $product = Product::where('sku', $this->data['MTCode'])->first() ?? new Product();
            $request = $this->accountantService->setProductRequest($product, $this->data);
            $this->storeProductService->execute($request, $product);
        } catch (\Exception|\Throwable $e) {
            Log::error(Logging::ACCOUNTANT_PRODUCT_SYNC_JOB_FAIL, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

    }
}
