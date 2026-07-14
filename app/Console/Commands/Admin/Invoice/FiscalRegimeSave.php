<?php

namespace App\Console\Commands\Admin\Invoice;

use App\Services\Synchronizers\Invoice\FiscalRegimeService;
use Illuminate\Console\Command;

class FiscalRegimeSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:fiscal-regime-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all invoice fiscal regimes from Odoo to local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $service = new FiscalRegimeService;
        $result = $service->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
