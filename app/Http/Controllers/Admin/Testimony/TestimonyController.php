<?php

namespace App\Http\Controllers\Admin\Testimony;

use App\Http\Controllers\Controller;

class TestimonyController extends Controller
{
    public function index() {
        return view('admin.testimony.index');
    }
}
