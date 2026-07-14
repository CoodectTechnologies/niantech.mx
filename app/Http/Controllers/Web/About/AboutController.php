<?php

namespace App\Http\Controllers\Web\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Banner;
use App\Models\Partner;
use App\Models\Team;
use App\Models\Testimony;

class AboutController extends Controller
{
    public function index() {
        $banner = Banner::getCache()?->filter(fn ($banner) => $banner->moduleWeb->name === 'Nosotros')?->first();
        $about = About::getCache();
        $team = Team::getCache();
        $testimonies = Testimony::getCache();
        $partners = Partner::getCache();

        return view('web.about.index', compact('about', 'team', 'testimonies', 'partners', 'banner'));
    }
}
