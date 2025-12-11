<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
            $data = array_filter(array_combine($headers, $row));

            // Sesuaikan ke model Anda
            switch ($tableName) {
                case 'userinfo':
                    $this->insertUserInfo($data);
                    break;
                case 'num_run':
                    $this->insertNumRun($data);
                    break;
                case 'num_run_deil':
                    $this->insertNumRunDeil($data);
                    break;
                case 'user_of_run':
                    $this->insertUserOfRun($data);
                    break;
                // Tambahkan case lain jika ada model lain
                default:
                    $this->error("Model tidak dikenali: $tableName");
                    return 1;
            }

            $count++;
        }

        fclose($file);
        $this->removeCsv($csvPath);
        $this->info("Import selesai. Total: $count baris.");
        return 0;
    }

    private function insertUserInfo($data){
        Employee::updateOrCreate(
            ['USERID' => $data['USERID']],
            $data
        );
    }
    private function insertUserOfRun($data){
        $data['STARTDATE'] = Carbon::createFromFormat('m/d/y H:i:s', $data['STARTDATE'])->format('Y-m-d H:i:s');
        $data['ENDDATE'] = Carbon::createFromFormat('m/d/y H:i:s', $data['ENDDATE'])->format('Y-m-d H:i:s');

        DB::connection('attdb')->table('user_of_run')->updateOrInsert(
            ['USERID' => $data['USERID']],
            $data
        );
    }

    private function insertNumRun($data){
        // Create a Carbon instance from the specific input format
        $data['STARTDATE'] = Carbon::createFromFormat('m/d/y H:i:s', $data['STARTDATE'])->format('Y-m-d H:i:s');
        $data['ENDDATE'] = Carbon::createFromFormat('m/d/y H:i:s', $data['ENDDATE'])->format('Y-m-d H:i:s');

        DB::connection('attdb')->table('num_run')->updateOrInsert(
            ['NUM_RUNID' => $data['NUM_RUNID']],
            $data
        );
    }
    private function insertNumRunDeil($data){
        DB::connection('attdb')->table('num_run_deil')->updateOrInsert(
            ['NUM_RUNDEILID' => $data['NUM_RUNDEILID']],
            $data
        );
    }

    private function removeCsv($csvPath){
        // ============================================
        // 3. Hapus file CSV setelah selesai
        // ============================================
        if (file_exists($csvPath)) {
            unlink($csvPath);
            $this->info("File CSV dihapus → $csvPath");
        } else {
            $this->warn("CSV sudah tidak ada, tidak perlu dihapus.");
        }
    }
}
