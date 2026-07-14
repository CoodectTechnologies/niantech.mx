<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\OdooException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     */
    protected string $redirectTo = '/';

    public function showResetForm(Request $request) {
        $token = $request->route()->parameter('token');

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
    protected function resetPassword(User $user, $password) {
        try {
            $this->setUserPassword($user, $password);
            $user->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
            $this->guard()->login($user);
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        } catch (OdooException $e) {
            report($e);
            Session::flash('alert', $e->getMessage());
            Session::flash('alert-type', 'warning');
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', __('There was an error saving your registration, please try again later.'));
            Session::flash('alert-type', 'warning');
        }
    }
}
