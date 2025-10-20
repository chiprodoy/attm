<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SyncAttlogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $date;
    private $appLog;

    /**
     * Create a new job instance.
     */
    public function __construct($date)
    {
        $this->appLog = Log::build([

            'driver' => 'daily',
            'path' => storage_path('logs/sync_att_log_job.log'),

        ]);

        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->appLog->info("SyncAttlogJob started for date {$this->date}");

        try {
            Artisan::call('sync:attlog', [
                '--date' => $this->date,
            ]);

            $this->appLog->info("SyncAttlogJob finished successfully for date {$this->date}");
        } catch (\Throwable $e) {
            $this->appLog->error("SyncAttlogJob failed: " . $e->getMessage());
        }
    }
}
