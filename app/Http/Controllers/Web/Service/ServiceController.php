<?php

namespace App\Http\Controllers\Web\Service;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index() {
        $banner = Banner::getCache()?->filter(fn ($banner) => $banner->moduleWeb->name === 'Servicios')?->first();
        $services = Service::getCache();

        return view('web.service.index', compact('services', 'banner'));
    }
    public function show(Service $service) {
        views($service)->cooldown(now()->addHours(1))->record();
        $services = Service::getCache();

        return view('web.service.show', compact('service', 'services'));
    }
}
