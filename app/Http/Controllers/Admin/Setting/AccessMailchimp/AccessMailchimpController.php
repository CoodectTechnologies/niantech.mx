<?php

namespace App\Http\Controllers\Admin\Setting\AccessMailchimp;

use App\Http\Controllers\Controller;

class AccessMailchimpController extends Controller
{
    public function index() {
        return view('admin.setting.access-mailchimp.index');
    }
}
