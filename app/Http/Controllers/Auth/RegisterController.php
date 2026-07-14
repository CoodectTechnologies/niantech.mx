<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\OdooException;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Rules\Recaptcha;
use App\Services\User\RegistrationService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected string $redirectTo = '/';

    public function __construct() {
        $this->middleware('guest');
    }
    public function showRegistrationForm() {
        $countries = Country::query()->where('status', true)->get();

        return view('auth.register', compact('countries'));
    }
    protected function validator(array $data) {
        return Validator::make($data, [
            'country' => ['required', 'integer', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'honey_recaptcha_token' => config('honey.recaptcha.status') ? ['required', new Recaptcha] : 'nullable',
        ]);
    }
    public function register(Request $request) {
        $this->validator($request->all())->validate();
        try {
            $registrationService = new RegistrationService;
            $user = $registrationService->register($request->all());

            return $this->registered($request, $user) ?? redirect($this->redirectPath());
        } catch (OdooException $e) {
            report($e);
            Session::flash('alert', $e->getMessage());
            Session::flash('alert-type', 'warning');

            return back()->withInput($request->except(['password', 'password_confirmation']));
        } catch (Exception $e) {
            report($e);

            return back()->withInput($request->except(['password', 'password_confirmation']))->withErrors([
                'email' => $e->getMessage(),
            ]);
        }
    }
    protected function registered(Request $request, $user) {
        if (! $user->hasVerifiedEmail()) {
            return Redirect::route('verification.notice', ['id' => Crypt::encrypt($user->id)]);
        } else {
            if (
                Route::has('ecommerce.home.index') &&
                $user->hasAnyRole(['Cliente']) &&
                Cart::instance('default')->count()
            ) {
                return Redirect::route('ecommerce.checkout.index');
            }
        }
    }
}
