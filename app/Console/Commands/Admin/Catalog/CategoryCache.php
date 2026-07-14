<?php

namespace App\Console\Commands\Admin\Catalog;

use App\Models\ProductCategory;
use Illuminate\Console\Command;

class CategoryCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:category-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate category cache';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        ProductCategory::regenerateCache();
        $this->info('Cache de categorias regenerado con éxito');
    }
}
