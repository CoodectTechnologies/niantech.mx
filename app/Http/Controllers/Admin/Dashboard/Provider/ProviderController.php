<?php

namespace App\Http\Controllers\Admin\Dashboard\Provider;

use App\Http\Controllers\Controller;

class ProviderController extends Controller
{
    public function index() {
        return view('admin.dashboard.provider.index');
    }
}
