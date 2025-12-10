<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class ImportAccessCommand extends Command
{
    protected $signature = 'import:access
                            {mdbfile : Path file MDB}
                            {table : Nama tabel dalam Access}
                            {tagetModel : Model Eloquent untuk import}';

    protected $description = 'Import data dari MS Access MDB ke MySQL';

    public function handle()
    {
        $mdbFile = $this->argument('mdbfile');
        $tableName = $this->argument('table');
        $tagetModel = $this->argument('tagetModel');

        if (!file_exists($mdbFile)) {
            $this->error("File tidak ditemukan: $mdbFile");
            return 1;
        }

        $csvPath = storage_path("app/access_{$tableName}.csv");

        // ================
        // 1. Jalankan mdb-export
        // ================
        $this->info("Mengekspor tabel [$tableName] dari [$mdbFile] ...");

        $process = new Process([
            'mdb-export',
            $mdbFile,
            $tableName
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $this->error("Gagal menjalankan mdb-export:");
            $this->error($process->getErrorOutput());
            return 1;
        }

        // simpan output CSV
        file_put_contents($csvPath, $process->getOutput());

        $this->info("Export selesai → $csvPath");

        // ================
        // 2. Import CSV ke MySQL
        // ================
        $this->info("Mengimpor data ke MySQL...");

        $file = fopen($csvPath, 'r');

        // skip header
        $headers = fgetcsv($file);

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($headers, $row);

            // Sesuaikan ke model Anda
            $tagetModel::updateOrCreate(
                $data
            );

            $count++;
        }

        fclose($file);

        $this->info("Import selesai. Total: $count baris.");
        return 0;
    }
    public function insertEmployee(){

    }
}
