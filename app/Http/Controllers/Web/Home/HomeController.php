<?php

namespace App\Http\Controllers\Web\Home;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Banner;
use App\Models\BlogPost;
use App\Models\Service;

class HomeController extends Controller
{
    public function index() {
        $banners = Banner::getCache()?->filter(fn ($banner) => $banner->moduleWeb->name === 'Inicio');
        $about = About::getCache();
        $services = Service::getCache()?->take(3);
        $posts = BlogPost::getCache()?->take(3);

        return view('web.home.index', compact('banners', 'about', 'services', 'posts'));
    }
}
