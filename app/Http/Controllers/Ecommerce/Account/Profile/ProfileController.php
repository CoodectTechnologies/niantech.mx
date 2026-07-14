<?php

namespace App\Http\Controllers\Ecommerce\Account\Profile;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index() {
        return view('ecommerce.account.profile.index');
    }
    public function password() {
        return view('ecommerce.account.profile.password');
    }
}
