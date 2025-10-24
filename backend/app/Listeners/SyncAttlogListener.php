<?php

namespace App\Listeners;

use App\Events\AttendanceReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SyncAttlogListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Waktu timeout (detik)
     */
    public $timeout = 300; // 5 menit

    /**
     * Handle the event.
     */
    public function handle(AttendanceReceived $event): void
    {
        $appLog = Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/syncattloglistener.log'),
        ]);
        try {
            $appLog->info("[Queue] SyncAttlogListener started for date {$event->date}");

            Artisan::call('sync:attlog', [
                '--date' => $event->date,
            ]);

            $appLog->info("[Queue] SyncAttlogListener finished successfully for date {$event->date}");
        } catch (\Throwable $e) {
            $appLog->error("[Queue] SyncAttlogListener failed: " . $e->getMessage());
        }
    }

    /**
     * Kapan listener dijalankan kembali jika gagal.
     */
    public function retryUntil()
    {
        return now()->addMinutes(10);
    }
}
