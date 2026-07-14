<?php

namespace App\Http\Controllers\Admin\Setting\ShippingClass;

use App\Http\Controllers\Controller;

class ShippingClassController extends Controller
{
    public function index() {
        return view('admin.setting.shipping-class.index');
    }
}
