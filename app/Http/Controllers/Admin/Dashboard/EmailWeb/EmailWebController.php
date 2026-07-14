<?php

namespace App\Http\Controllers\Admin\Dashboard\EmailWeb;

use App\Http\Controllers\Controller;

class EmailWebController extends Controller
{
    public function index() {
        return view('admin.dashboard.email-web.index');
    }
}
