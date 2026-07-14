<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    public function index() {
        return view('admin.catalog.warehouse.index');
    }
}
