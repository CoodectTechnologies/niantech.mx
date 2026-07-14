<?php

namespace App\Http\Controllers\Admin\Invoice;

use App\Http\Controllers\Controller;

class FiscalRegimeController extends Controller
{
    public function index() {
        return view('admin.invoice.fiscal-regime.index');
    }
}
