<?php

namespace App\Http\Controllers\Admin\Dashboard\Order;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index() {
        return view('admin.dashboard.order.index');
    }
}
