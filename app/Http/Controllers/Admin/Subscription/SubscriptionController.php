<?php

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index() {
        return view('admin.subscription.subscription.index');
    }
}
