<?php

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Controller;

class PlanFeatureController extends Controller
{
    public function index() {
        return view('admin.subscription.plan-feature.index');
    }
}
