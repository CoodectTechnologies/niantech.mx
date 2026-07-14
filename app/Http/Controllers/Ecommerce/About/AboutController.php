<?php

namespace App\Http\Controllers\Ecommerce\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Product;
use App\Models\ProductBrand;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index() {
        $about = About::first() ?? new About;
        $productsCount = Product::validateProduct()->count();
        $productBrandsCount = ProductBrand::count();
        $partners = Cache::get('partners') ?? [];

        return view('ecommerce.about.index', compact('about', 'productsCount', 'productBrandsCount', 'partners'));
    }
}
