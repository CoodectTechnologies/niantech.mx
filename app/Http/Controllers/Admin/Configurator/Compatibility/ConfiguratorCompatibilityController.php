<?php

namespace App\Http\Controllers\Admin\Configurator\Compatibility;

use App\Http\Controllers\Controller;
use App\Models\ConfiguratorCompatibility;

class ConfiguratorCompatibilityController extends Controller
{
    public function index() {
        return view('admin.configurator.compatibility.index');
    }
    public function create() {
        return view('admin.configurator.compatibility.create');
    }
    public function edit(ConfiguratorCompatibility $configuratorCompatibility) {
        return view('admin.configurator.compatibility.edit', compact('configuratorCompatibility'));
    }
}
