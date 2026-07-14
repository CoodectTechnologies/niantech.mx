<?php

namespace App\Http\Controllers\Install\Complete;

use App\Http\Controllers\Controller;

class CompleteController extends Controller
{
    public function index() {
        return view('install.complete.index');
    }
}
