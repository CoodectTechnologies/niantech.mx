<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;

class UnitTypeController extends Controller
{
    public function index() {
        return view('admin.catalog.unit-type.index');
    }
}
