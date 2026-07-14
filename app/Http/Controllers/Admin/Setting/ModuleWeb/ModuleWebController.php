<?php

namespace App\Http\Controllers\Admin\Setting\ModuleWeb;

use App\Http\Controllers\Controller;

class ModuleWebController extends Controller
{
    public function index() {
        return view('admin.setting.module-web.index');
    }
}
