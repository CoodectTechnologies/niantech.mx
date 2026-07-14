<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index() {
        return view('admin.catalog.brand.index');
    }
}
