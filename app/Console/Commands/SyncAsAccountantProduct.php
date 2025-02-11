<?php

namespace App\Console\Commands;

use App\Constants\ClientResponse;
use App\Constants\Logging;
use App\Infrastructure\Services\AsAccountantService;
use App\Jobs\AsAccountantProductSyncJob;
use Botble\Ecommerce\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAsAccountantProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:AsAccountantProduct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync As Accountant Product.';

    public AsAccountantService $asAccountantService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AsAccountantService $asAccountantService)
    {
        parent::__construct();
        $this->asAccountantService = $asAccountantService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $response = $this->asAccountantService->getProducts();
            if ($response['status'] == ClientResponse::STATUS['success']) {
                foreach ($response['data'] as $row) {
                    AsAccountantProductSyncJob::dispatch($row)->onQueue('high');
                }
            }
        } catch (\Exception|\Throwable $e) {
            Log::error(Logging::ACCOUNTANT_PRODUCT_SYNC_COMMAND_FAIL, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $this->info('Started: Sync As Accountant Products.');
    }
}
