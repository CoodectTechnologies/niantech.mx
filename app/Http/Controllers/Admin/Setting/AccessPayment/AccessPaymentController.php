<?php

namespace App\Http\Controllers\Admin\Setting\AccessPayment;

use App\Http\Controllers\Controller;

class AccessPaymentController extends Controller
{
    public function index() {
        return view('admin.setting.access-payment.index');
    }
}
