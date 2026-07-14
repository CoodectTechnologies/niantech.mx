<?php

namespace App\Http\Controllers\Admin\Setting\Currency;

use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function index() {
        return view('admin.setting.currency.index');
    }
}
