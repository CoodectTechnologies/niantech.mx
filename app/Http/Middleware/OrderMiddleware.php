<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $user = User::find($request->order->user_id);
        $userPresent = User::find(Auth::id());
        if (
            $user->id === $userPresent->id ||
            $userPresent->canAny(['usuarios', 'ordenes'])
        ) {
            return $next($request);
        } else {
            session()->flash('alert', 'No tienes los permisos suficientes');
            session()->flash('alert-type', 'warning');

            return Redirect::back();
        }
    }
}
