<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class TestExcelRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test reading Excel files from storage/app/data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = [
            'PatDetails.xlsx',
            'DiagnosisMaster.xlsx',
            'EyeExamination.xlsx',
        ];

        foreach ($files as $file) {
            $path = storage_path("app/data/{$file}");

            if (!file_exists($path)) {
                $this->error("❌ File not found: {$path}");
                continue;
            }

            try {
                $sheets = Excel::toArray([], $path);
                $rows = count($sheets[0] ?? []);
                $this->info("✅ Successfully read {$file} ({$rows} rows)");
            } catch (\Exception $e) {
                $this->error("⚠️ Error reading {$file}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
