<?php

namespace App\Http\Controllers\Ecommerce\Compare;

use App\Http\Controllers\Controller;

class CompareController extends Controller
{
    public function index() {
        return view('ecommerce.compare.index');
    }
}
