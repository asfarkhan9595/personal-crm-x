<?php

namespace App\Console\Commands;

use Database\Seeders\CrmInitialSheetSeeder;
use Illuminate\Console\Command;

class CrmImportSheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'crm:import-sheet {--force : Force re-importing data}';

    /**
     * The console command description.
     */
    protected $description = 'Import initial 8 leads and 3 payments from Google Sheet or seeded template';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting CRM Sheet Import...');
        $seeder = new CrmInitialSheetSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('CRM initial data ready.');
        return self::SUCCESS;
    }
}
