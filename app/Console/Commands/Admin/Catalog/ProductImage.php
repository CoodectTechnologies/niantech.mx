<?php

namespace App\Console\Commands\Admin\Catalog;

use App\Services\Synchronizers\Catalog\ProductController;
use Illuminate\Console\Command;

class ProductImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:product-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create product's images";

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
        $result = [];
        $catalog = new ProductController;
        $result = $catalog->images();
        $this->info(json_encode($result, JSON_PRETTY_PRINT));
    }
}
