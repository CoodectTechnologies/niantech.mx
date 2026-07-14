<?php

namespace App\Http\Controllers\Admin\Invoice;

use App\Http\Controllers\Controller;

class UseCfdiController extends Controller
{
    public function index() {
        return view('admin.invoice.use-cfdi.index');
    }
}
