<?php

namespace App\Http\Controllers\Admin\Setting\Configurator;

use App\Http\Controllers\Controller;

class ConfiguratorController extends Controller
{
    public function index() {
        return view('admin.setting.configurator.index');
    }
}
