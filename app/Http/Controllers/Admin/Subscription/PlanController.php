<?php

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index() {
        return view('admin.subscription.plan.index');
    }
}
