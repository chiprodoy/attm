<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use Exception;

class ImportFromAccess extends Command
{
    protected $signature = 'import:access
                            {table : Nama tabel yang akan diimport}
                            {--file= : Path file .mdb (opsional, default pakai DSN)}
                            {--upsert= : Nama kolom primary key untuk updateOrInsert}';

    protected $description = 'Import tabel dari Access (.mdb) ke MySQL secara generic (support upsert)';

    public function handle()
    {
        $table = $this->argument('table');
        $file = $this->option('file');
        $upsertKey = $this->option('upsert'); // contoh: DEPTID atau USERID
        $dsn = "odbc:AccessDB"; // sesuai DSN di odbc.ini

        if ($file) {
            $dsn = "odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq={$file};";
        }

        try {
            $this->info("🔌 Koneksi ke Access...");
            $pdo = new PDO($dsn, '', ''); // MDB biasanya tanpa user/pass

            $this->info("📥 Ambil data dari tabel: {$table}");
            $stmt = $pdo->query("SELECT * FROM {$table}");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                $this->warn("⚠️ Tidak ada data ditemukan di tabel {$table} (Access).");
                return Command::SUCCESS;
            }

            $this->info("⏳ Import ke MySQL...");
            DB::connection("attdb")->transaction(function () use ($table, $rows, $upsertKey) {
                foreach ($rows as $row) {
                    if ($upsertKey && isset($row[$upsertKey])) {
                        // updateOrInsert jika ada key unik
                        DB::connection("attdb")->table($table)->updateOrInsert(
                            [$upsertKey => $row[$upsertKey]], // kondisi pencarian
                            $row // data update
                        );
                    } else {
                        // default insert biasa
                        DB::connection("attdb")->table($table)->insert($row);
                    }
                }
            });

            $this->info("✅ Import selesai! Total: " . count($rows) . " baris di tabel {$table}.");

        } catch (Exception $e) {
            $this->error("❌ Gagal import: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
