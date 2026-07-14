<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class ImpersonateController extends Controller
{
    public function signin(User $user) {
        Session::put('impersonated_by', Auth::id());
        Cart::destroy();
        Auth::login($user);
        if (Route::has('ecommerce.cart.index')) {
            Cart::instance('default')->restore(Auth::id());
        }
        if (Route::has('ecommerce.wishlist.index')) {
            Cart::instance('wishlist')->restore(Auth::id());
        }
        if (Route::has('ecommerce.compare.index')) {
            Cart::instance('compare')->restore(Auth::id());
        }
        if (Route::has('ecommerce.home.index')) {
            return Redirect::route('ecommerce.home.index');
        } else {
            return Redirect::route('web.home.index');
        }
    }
    public function logout() {
        if (! $impersonatedBy = Session::get('impersonated_by')) {
            Session::flash('alert', 'No existe un usuario al cual regresar');
            Session::flash('alert-type', 'warning');
            if (Route::has('ecommerce.home.index')) {
                return Redirect::route('ecommerce.home.index');
            } else {
                return Redirect::route('web.home.index');
            }
        }
        $userInSession = User::find(Auth::id());
        $user = User::find($impersonatedBy);
        Cart::destroy();
        Auth::login($user);

        if (Route::has('ecommerce.cart.index')) {
            Cart::instance('default')->restore(Auth::id());
        }
        if (Route::has('ecommerce.wishlist.index')) {
            Cart::instance('wishlist')->restore(Auth::id());
        }
        if (Route::has('ecommerce.compare.index')) {
            Cart::instance('compare')->restore(Auth::id());
        }
        
        Session::put('impersonated_by', null);

        return Redirect::route('admin.user.show', $userInSession);
    }
}
