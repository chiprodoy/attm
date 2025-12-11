<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class ImportAccessCommand extends Command
{
    protected $signature = 'import:access
                            {mdbfile : Path file MDB}
                            {table : Nama tabel dalam Access}';

    protected $description = 'Import data dari MS Access MDB ke MySQL';

    public function handle()
    {
        $mdbFile = $this->argument('mdbfile');
        $tableName = $this->argument('table');

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
            switch ($tableName) {
                case 'userinfo':
                    $this->insertUserInfo($data);
                    break;
                // Tambahkan case lain jika ada model lain
                default:
                    $this->error("Model tidak dikenali: $tableName");
                    return 1;
            }

            $count++;
        }

        fclose($file);

        $this->info("Import selesai. Total: $count baris.");
        return 0;
    }

    private function insertUserInfo($data){
        Employee::updateOrCreate(
            ['USERID' => $data['USERID']],
            $data
        );
    }
}
