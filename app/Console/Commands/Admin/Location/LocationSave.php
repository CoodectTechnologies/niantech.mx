<?php

namespace App\Console\Commands\Admin\Location;

use App\Services\Synchronizers\Location\LocationService;
use Illuminate\Console\Command;

class LocationSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync countries and states from Odoo to local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $locationService = new LocationService;
        $result = $locationService->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
