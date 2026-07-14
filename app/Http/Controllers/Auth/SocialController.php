<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\OdooException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\RegistrationService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function googleRedirect() {
        return Socialite::driver('google')->redirect();
    }
    public function loginWithGoogle() {
        try {
            $userSocial = Socialite::driver('google')->user();
            $user = User::where('email', $userSocial->email)->first();
            if (! $user) {
                try {
                    $registrationService = new RegistrationService;
                    $user = $registrationService->register([
                        'name' => $userSocial->name,
                        'email' => $userSocial->email,
                        'password' => null,
                    ], true);
                    $user->image()->create(['url' => $userSocial->avatar, 'name' => $userSocial->avatar]);
                } catch (OdooException $e) {
                    report($e);
                    Session::flash('alert', $e->getMessage());
                    Session::flash('alert-type', 'warning');

                    return Redirect::route('login');
                } catch (Exception $e) {
                    report($e);
                    Session::flash('alert', __('There was an error saving your registration, please try again later.'));
                    Session::flash('alert-type', 'warning');

                    return Redirect::route('login');
                }
            }
            if (! $user->connected_google) {
                Session::flash('alert', __('Google login has been disabled. contact support'));
                Session::flash('alert-type', 'warning');

                return Redirect::route('login');
            } else {
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
                if ($user->accessToPanel()) {
                    return Redirect::route('admin.dashboard.general.index');
                } else {
                    if (Route::has('ecommerce.home.index')) {
                        return Redirect::route('ecommerce.home.index');
                    } else {
                        return Redirect::route('web.home.index');
                    }
                }
            }
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', __('Login not complete').': '.$e->getMessage());
            Session::flash('alert-type', 'warning');

            return Redirect::route('login');
        }
    }
}
