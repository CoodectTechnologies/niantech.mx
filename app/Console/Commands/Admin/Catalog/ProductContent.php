<?php

namespace App\Console\Commands\Admin\Catalog;

use App\Services\Synchronizers\Catalog\ProductController;
use Illuminate\Console\Command;

class ProductContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:product-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronization product content (attributes, characteristics, description)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $product = new ProductController;
        $result = $product->content();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));
    }
}
