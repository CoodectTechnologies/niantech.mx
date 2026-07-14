<?php

namespace App\Http\Controllers\Web\Contact;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class ContactController extends Controller
{
    public function index() {
        $banner = Banner::getCache()?->filter(fn ($banner) => $banner->moduleWeb->name === 'Contacto')?->first();

        return view('web.contact.index', compact('banner'));
    }
}
