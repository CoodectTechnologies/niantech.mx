<?php

namespace App\Http\Controllers\Admin\Setting\Welcome;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index() {
        return view('admin.setting.welcome.index');
    }
}
