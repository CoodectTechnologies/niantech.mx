<?php

namespace App\Http\Controllers\Admin\Setting\Contact;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index() {
        return view('admin.setting.contact.index');
    }
}
