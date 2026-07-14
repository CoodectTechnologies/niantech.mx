<?php

namespace App\Http\Controllers\Admin\Setting\General;

use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function index() {
        return view('admin.setting.general.index');
    }
}
