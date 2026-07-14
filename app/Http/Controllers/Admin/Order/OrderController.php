<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index() {
        return view('admin.order.order.index');
    }
    public function show(Order $order) {
        return view('admin.order.order.show', compact('order'));
    }
}
