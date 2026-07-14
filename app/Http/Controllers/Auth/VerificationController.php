<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\User\UserCreate;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     */
    protected string $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request) {
        if (! $request->id) {
            return Redirect::route('login');
        }
        $userId = Crypt::decrypt($request->id);
        $user = User::where('id', $userId)->first();
        if (! $user) {
            Session::flash('alert', __('User not found'));
            Session::flash('alert-type', 'warning');

            return Redirect::route('login');
        }

        return $user->hasVerifiedEmail() ? Redirect::to($this->redirectPath()) : view('auth.verify');
    }
    public function verify(Request $request) {
        $user = User::find($request->route('id'));
        if (! $user) {
            Session::flash('alert', __('User not found'));
            Session::flash('alert-type', 'warning');

            return Redirect::route('login');
        }
        if ($user->hasVerifiedEmail()) {
            Session::flash('alert', __('Your email is now verified. Now you can log in.'));
            Session::flash('alert-type', 'warning');

            return Redirect::route('login');
        }
        $user->markEmailAsVerified();
        $user->notify(new UserCreate);
        event(new Verified($user));
        Auth::login($user);

        return Redirect::to($this->redirectTo);
    }
    public function resend(Request $request) {
        try {
            $userId = Crypt::decrypt($request->id);
            $user = User::find($userId);
            if ($user->hasVerifiedEmail()) {
                return $request->wantsJson()
                            ? new JsonResponse([], 204)
                            : redirect($this->redirectPath());
            }

            $user->sendEmailVerificationNotification();

            return $request->wantsJson()
                        ? new JsonResponse([], 202)
                        : back()->with('resent', true);
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', $e->getMessage());
            Session::flash('alert-type', 'danger');

            return Redirect::back();
        }
    }
}
