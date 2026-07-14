<?php

namespace App\Http\Controllers\Ecommerce\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    public function __invoke($type) {
        Session::put('theme-type', $type);

        return Redirect::back();
    }
}
