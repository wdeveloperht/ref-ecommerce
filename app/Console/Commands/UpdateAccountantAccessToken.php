<?php

namespace App\Console\Commands;

use App\Constants\ClientResponse;
use App\Constants\Logging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateAccountantAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:accessToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update AS-Accountant Access Token.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $status = ClientResponse::STATUS['error'];
            $config = config('accountant.refreshAccessToken');
            $body = [
                'ClientId' => config('accountant.ClientId'),
                'Secret' => config('accountant.Secret'),
                'DBId' => config('accountant.DBId')
            ];

            $request = client()->{$config['method']}($config['url']) // ['verify' => false]
            ->setHeaders($config['headers'])
                ->setBody($body)
                ->setLogMessage(Logging::ACCOUNTANT_API_REFRESH_ACCESS_TOKEN_REQUEST)
                ->send();

            if ($request->getStatusCode() === Response::HTTP_OK) {
                $response = $request->getBody();
                if (!empty($response['accessToken'])) {
                    $status = ClientResponse::STATUS['success'];
                    setting()
                        ->set([
                            'accountant_access_token' => $response['accessToken']
                        ])
                        ->save();
                }
            }
        } catch (\Exception $e) {
            Log::error(Logging::ACCOUNTANT_API_REFRESH_ACCESS_TOKEN_REQUEST_FAIL, [
                'msg' => $e->getMessage(),
                'tress' => $e->getTraceAsString()
            ]);
        }
        if ($status) {
            $this->info('Success: Access Token Successfully Updated.');
        } else {
            $this->error('Error: Sorry, something went wrong.');
        }
    }
}
