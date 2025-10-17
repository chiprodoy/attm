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

    /**
     * Create a new job instance.
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("SyncAttlogJob started for date {$this->date}");

        try {
            Artisan::call('sync:attlog', [
                '--date' => $this->date,
            ]);

            Log::info("SyncAttlogJob finished successfully for date {$this->date}");
        } catch (\Throwable $e) {
            Log::error("SyncAttlogJob failed: " . $e->getMessage());
        }
    }
}
