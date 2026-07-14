<?php

namespace App\Http\Controllers\Admin\Dashboard\General;

use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function index() {
        return view('admin.dashboard.general.index');
    }
}
