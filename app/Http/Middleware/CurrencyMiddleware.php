<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Ecommerce\Currency\CurrencyController;
use App\Models\Currency;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $currency = Currency::getDefault();
        $currencies = Currency::getCache();
        $currencyCode = $currency->code;
        $countriesLocales = config('translatable.countries');
        if (
            Session::has('currency') &&
            in_array(Session::get('currency'), $currencies->pluck('code')->toArray())
        ) {
            $currencyCode = Session::get('currency');
        } elseif (
            Session::has('currency') &&
            ! in_array(Session::get('currency'), $currencies->pluck('code')->toArray())
        ) {
            CurrencyController::cartCurrencySwitcher($currencyCode);
        }
        $this->putCurrency($currencyCode);

        return $next($request);
    }

    private function putCurrency($currencyCode) {
        Session::put('currency', $currencyCode);
    }
}
