<?php

namespace App\Http\Controllers\Admin\Configurator\Performance;

use App\Http\Controllers\Controller;

class ConfiguratorPerformanceController extends Controller
{
    public function index() {
        return view('admin.configurator.performance.index');
    }
}
