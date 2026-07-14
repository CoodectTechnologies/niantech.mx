<?php

namespace App\Http\Controllers\Admin\Setting\Invoice;

use App\Http\Controllers\Controller;

class CredentialController extends Controller
{
    public function index() {
        return view('admin.setting.invoice.credential.index');
    }
}
