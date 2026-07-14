<?php

namespace App\Http\Controllers\Admin\Setting\State;

use App\Http\Controllers\Controller;

class StateController extends Controller
{
    public function index() {
        return view('admin.setting.state.index');
    }
}
