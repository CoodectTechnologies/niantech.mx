<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index() {
        return view('admin.banner.index');
    }
}
