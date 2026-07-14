<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $language = config('translatable.fallback');
        $languagesLocales = config('translatable.locales');
        $countriesLocales = config('translatable.countries');

        if (config('translatable.status')) {
            if (
                Session::has('language') &&
                App::getLocale() != Session::get('language') &&
                isset($languagesLocales[Session::get('language')])
            ) {
                $language = Session::get('language');
            }
        }
        $this->putLanguage($language);
        return $next($request);
    }

    private function putLanguage($language) {
        Session::put('language', $language);
        App::setLocale($language);
    }
}
