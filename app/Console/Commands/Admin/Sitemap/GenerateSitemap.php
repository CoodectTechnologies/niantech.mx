<?php

namespace App\Console\Commands\Admin\Sitemap;

use App\Models\BlogPost;
use App\Models\PrivacyNotice;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

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
        Sitemap::create()
        /* URLS MANUALES */
            ->add(Url::create(route('ecommerce.home.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.about.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.blog.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.contact.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.category.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.product.index'))->setPriority(0.1))
            ->add(Url::create(route('ecommerce.cart.index'))->setPriority(0.2))
            ->add(Url::create(route('ecommerce.wishlist.index'))->setPriority(0.2))
            ->add(Url::create(route('ecommerce.compare.index'))->setPriority(0.2))
            ->add(Url::create(route('ecommerce.track-order.index'))->setPriority(0.2))
        // URLS POLICES
            ->add(PrivacyNotice::cursor())
        // URLS POSTS
            ->add(BlogPost::validatePost()->cursor())
        // URLS PRODUCTS
            ->add(Product::validateProduct()->cursor())
        // URLS CATEGORIES
            ->add(ProductCategory::validateCategory()->whereNull('parent_id')->cursor())
        // HACER SITEMAP
            ->writeToFile(public_path('sitemap.xml'));
    }
}
