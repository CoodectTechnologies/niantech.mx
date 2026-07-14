<?php

namespace App\Http\Controllers\Admin\Setting\Permission;

use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index() {
        return view('admin.setting.permission.index');
    }
}
