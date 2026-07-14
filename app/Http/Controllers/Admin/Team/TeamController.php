<?php

namespace App\Http\Controllers\Admin\Team;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index() {
        return view('admin.team.index');
    }
}
