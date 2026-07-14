<?php

namespace App\Http\Controllers\Admin\Setting\Popup;

use App\Http\Controllers\Controller;

class PopupController extends Controller
{
    public function index() {
        return view('admin.setting.popup.index');
    }
}
