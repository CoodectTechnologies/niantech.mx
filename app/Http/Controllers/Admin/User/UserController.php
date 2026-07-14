<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('can:usuarios')->only('index');
        $this->middleware('user')->except('index', 'profile');
    }
    public function index() {
        return view('admin.user.user.index');
    }
    public function show(User $user) {
        return view('admin.user.user.show', compact('user'));
    }
    public function profile() {
        $user = User::find(Auth::id());

        return view('admin.user.user.show', compact('user'));
    }
}
