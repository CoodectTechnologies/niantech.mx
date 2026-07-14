<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function index() {
        return view('admin.package.package.index');
    }
}
