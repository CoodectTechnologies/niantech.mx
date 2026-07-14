<?php

namespace App\Http\Controllers\Ecommerce\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\Product;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index() {
        $bannersPrimary = Banner::whereRelation('moduleWeb', 'name', 'Inicio')->with(['image'])->orderBy('order')->get();
        $bannerCallToActions = Banner::whereRelation('moduleWeb', 'name', 'Inicio - call to action')->orderBy('order')->get();
        $productsFeatured = Product::query()->validateProduct()->withRelations()->where('featured', true)->take(10)->get();
        $productsMostSelled = Product::query()->validateProduct()->withRelations()->mostSelled()->take(10)->get();
        $productsNew = Product::query()->validateProduct()->withRelations()->whereDate('created_at', '>=', Carbon::now()->subDays(Product::DAYS_IS_NEW))->orderByDesc('id')->take(10)->get();
        $productsViewRecents = Product::getViewRecents();
        $popup = Popup::query()->where('active', true)->orderByDesc('id')->first() ?? new Popup;
        $partners = Partner::getCache();
        $comments = Comment::with('commentable')->validate()->where('commentable_type', Product::class)->where('stars', '>=', 4)->latest()->take(10)->get();

        return view('ecommerce.home.index', compact('bannersPrimary', 'bannerCallToActions', 'productsFeatured', 'productsMostSelled', 'popup', 'partners', 'productsViewRecents', 'productsNew', 'comments'));
    }
}
