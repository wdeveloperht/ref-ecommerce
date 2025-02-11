<?php

namespace App\Console;

use App\Console\Commands\SyncAsAccountantProduct;
use App\Console\Commands\UpdateAccountantAccessToken;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(UpdateAccountantAccessToken::class)->daily()->at('06:00');
        $schedule->command(SyncAsAccountantProduct::class)->daily()->at('04:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
