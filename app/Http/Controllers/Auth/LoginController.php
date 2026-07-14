<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PrivacyNotice;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        $privacyNotices = PrivacyNotice::getCache();
        return view('auth.login', compact('privacyNotices'));
    }
    public function authenticated($request, $user) {
        if (! $user->hasVerifiedEmail()) {
            return Redirect::route('verification.notice', ['id' => Crypt::encrypt($user->id)]);
        }
        if (Route::has('ecommerce.cart.index')) {
            Cart::instance('default')->restore(Auth::id());
        }
        if (Route::has('ecommerce.wishlist.index')) {
            Cart::instance('wishlist')->restore(Auth::id());
        }
        if (Route::has('ecommerce.compare.index')) {
            Cart::instance('compare')->restore(Auth::id());
        }
    }
}
