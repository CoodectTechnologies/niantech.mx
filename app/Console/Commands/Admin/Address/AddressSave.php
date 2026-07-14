<?php

namespace App\Console\Commands\Admin\Address;

use App\Services\Synchronizers\Address\AddressService;
use Illuminate\Console\Command;

class AddressSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'address:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all addresses by odoo to local';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $addressService = new AddressService;
        $result = $addressService->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
