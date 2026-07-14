<?php

namespace App\Console\Commands\Admin\Odoo;

use Database\Seeders\ShippingZoneSeeder;
use Illuminate\Console\Command;

class SaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odoo:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save all Odoo data to local';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $this->newLine();
        $this->line('========================================');
        $this->line('     ODOO DATA SYNCHRONIZATION          ');
        $this->line('========================================');
        $this->newLine();
        $this->components->task('Saving locations', fn () => $this->call('location:save'));
        $this->components->task('Saving uses cfdi', fn () => $this->call('invoice:use-cfdi-save'));
        $this->components->task('Saving fiscal regimes', fn () => $this->call('invoice:fiscal-regime-save'));
        $this->components->task('Saving users', fn () => $this->call('user:save'));
        $this->components->task('Saving addresses', fn () => $this->call('address:save'));
        $this->components->task('Saving products', fn () => $this->call('catalog:product-save'));
        $this->components->task('Saving warehouses', fn () => $this->call('catalog:product-warehouse'));
        $this->newLine();
        $this->call(ShippingZoneSeeder::class); // Como el comando locations borra los estados locales, para crear los de odoo, volvemos a correr el seeder
        $this->components->info('All Odoo data has been synchronized');
    }
}
