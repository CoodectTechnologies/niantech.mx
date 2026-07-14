<?php

namespace App\Console\Commands\Admin\Catalog;

use App\Services\Synchronizers\Catalog\ProductController;
use Illuminate\Console\Command;

class ProductSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:product-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronization catalog (products, categories, brands, attributes, characteristics)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $product = new ProductController;
        $result = $product->save();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));
    }
}
