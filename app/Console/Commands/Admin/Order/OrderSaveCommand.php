<?php

namespace App\Console\Commands\Admin\Order;

use App\Services\Synchronizers\Order\OrderService;
use Illuminate\Console\Command;

class OrderSaveCommand extends Command
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
    protected $description = 'Send all orders to external';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        OrderService::save();
    }
}
