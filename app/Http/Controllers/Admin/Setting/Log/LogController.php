<?php

namespace App\Http\Controllers\Admin\Setting\Log;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index() {
        return view('admin.setting.log.index');
    }
}
