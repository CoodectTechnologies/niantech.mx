<?php

namespace App\Http\Controllers\Admin\Setting\Warehouse;

use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    public function index() {
        return view('admin.setting.warehouse.index');
    }
}
