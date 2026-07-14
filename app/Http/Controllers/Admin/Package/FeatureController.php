<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Controller;

class FeatureController extends Controller
{
    public function index() {
        return view('admin.package.feature.index');
    }
}
