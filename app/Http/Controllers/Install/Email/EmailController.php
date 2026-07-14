<?php

namespace App\Http\Controllers\Install\Email;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class EmailController extends Controller
{
    public function index() {
        return view('install.email.index');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'mailer' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'encriptation' => 'nullable',
        ]);
        try {
            setEnvValue('MAIL_MAILER', $validated['mailer']);
            setEnvValue('MAIL_HOST', $validated['host']);
            setEnvValue('MAIL_PORT', $validated['port']);
            setEnvValue('MAIL_USERNAME', $validated['username']);
            setEnvValue('MAIL_FROM_ADDRESS', $validated['username']);
            setEnvValue('MAIL_PASSWORD', $validated['password']);
            setEnvValue('MAIL_ENCRYPTION', $validated['encriptation']);

            return Redirect::route('install.user.index');
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', 'Ocurrio un error: '.$e->getMessage());
            Session::flash('alert-type', 'warning');

            return Redirect::route('install.email.index');
        }
    }
}
