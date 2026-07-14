<?php

namespace App\Http\Controllers\Ecommerce\Test;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index() {
        return view('ecommerce.test.index');
    }
}
