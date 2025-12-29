<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clearlogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = glob(storage_path('logs/*.log')); // Get all log files

        foreach ($files as $file) {
            if (File::exists($file)) {
                    // Delete the file. Consider using 'unlink($file)' or 'File::delete($file)'
                    unlink($file);
                }
            }
            $this->info('Logs have been cleared!'); // Output success message

    }
}
