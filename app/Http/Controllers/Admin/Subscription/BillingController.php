<?php

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index() {
        $user = User::find(Auth::id());

        return view('admin.subscription.billing.index', compact('user'));
    }
}
