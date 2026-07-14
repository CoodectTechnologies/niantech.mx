<?php

namespace App\Http\Middleware;

use App\Models\Plan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $ability = null): Response {
        if ($ability) {
            $user = $request->user();
            if ($user->can($ability)) {
                return $next($request);
            } else {
                $subscription = $user->subscriptionActive;
                if (! $subscription) {
                    Session::flash('alert', 'Necesitas una suscripción activa');
                    Session::flash('alert-type', 'warning');

                    return Redirect::route('admin.subscription.billing.index');
                }
                if (! $subscription->plan->hasPermissionTo($ability)) {
                    Session::flash('alert', 'Considerá hacer un upgrate de tu plan');
                    Session::flash('alert-type', 'info');

                    return Redirect::route('admin.subscription.billing.index');
                }
            }
        } else {
            $stripeProductName = Plan::query()->whereNotNull('stripe_product_name')->value('stripe_product_name');
            $subscriptionName = $stripeProductName;
            if (! $request->user()?->subscribed($subscriptionName)) {
                Session::flash('alert', 'Necesitas una suscripción activa');
                Session::flash('alert-type', 'warning');

                return Redirect::route('admin.subscription.billing.index');
            }
        }

        return $next($request);
    }
}
