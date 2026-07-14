<?php

namespace App\Http\Controllers\Admin\Configurator\Game;

use App\Http\Controllers\Controller;

class ConfiguratorGameController extends Controller
{
    public function index() {
        return view('admin.configurator.game.index');
    }
}
