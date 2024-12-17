<?php

namespace App\Listeners;

use App\Events\AsAccountantProductSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AsAccountantProductSyncListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AsAccountantProductSync $event): void
    {
//       dd($event);
    }
}
