<?php

namespace App\Http\Controllers\Ecommerce\Account\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index() {
        return view('ecommerce.account.dashboard.index');
    }
}
