<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SyncAttLogCommand::class,
        \App\Console\Commands\InitCheckLogStatusCommand::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('sync:attlog',['--date'=>'2025-08-01'])->everySecond();
        $schedule->command('ecls:init')->dailyAt('00:01');
       // $schedule->command('sync:attlog',['--date'=>Carbon::today()->toDateString()])->everyMinute();

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
