<?php

namespace App\Console\Commands\Admin\Invoice;

use App\Services\Synchronizers\Invoice\UseCfdiService;
use Illuminate\Console\Command;

class UseCfdiSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:use-cfdi-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all invoice use CFDI from Odoo to local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $service = new UseCfdiService;
        $result = $service->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
