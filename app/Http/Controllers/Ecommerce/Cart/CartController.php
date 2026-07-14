<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index() {
        return view('ecommerce.cart.index');
    }
}
