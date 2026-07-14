<?php

namespace App\Http\Controllers\Admin\Setting\Integration;

use App\Http\Controllers\Controller;

class IntegrationController extends Controller
{
    public function index() {
        return view('admin.setting.integration.index');
    }
}
