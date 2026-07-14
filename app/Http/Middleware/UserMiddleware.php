<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $user = User::find($request->user->id);
        $userPresent = User::find(Auth::user()->id);
        if ($user->id === $userPresent->id || $userPresent->can('usuarios')) {
            return $next($request);
        } else {
            Session::flash('alert', 'No tienes los permisos suficientes');
            Session::flash('alert-type', 'warning');

            return redirect()->route('admin.dashboard.general.index');
        }
    }
}
