<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;

class GenderController extends Controller
{
    public function index() {
        return view('admin.catalog.gender.index');
    }
}
