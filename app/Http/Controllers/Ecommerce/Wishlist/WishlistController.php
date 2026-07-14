<?php

namespace App\Http\Controllers\Ecommerce\Wishlist;

use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function index() {
        return view('ecommerce.wishlist.index');
    }
}
