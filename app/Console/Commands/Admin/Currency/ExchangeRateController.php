<?php

namespace App\Console\Commands\Admin\Currency;

use App\Services\Synchronizers\Currency\CurrencyController;
use Illuminate\Console\Command;

class ExchangeRateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all values (exchange rate) by currencies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        CurrencyController::saveExchangeRate();
    }
}
