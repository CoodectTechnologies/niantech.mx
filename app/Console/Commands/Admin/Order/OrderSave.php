<?php

namespace App\Console\Commands\Admin\Order;

use App\Services\Synchronizers\Order\OrderController as OrderControllerProvider;
use Illuminate\Console\Command;

class OrderSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all orders to provider';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        OrderControllerProvider::save();
    }
}
