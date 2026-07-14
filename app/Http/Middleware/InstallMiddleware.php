<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

class InstallMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // $install = false;
        $envRequired = [
            'APP_NAME',
            'APP_URL',
            'APP_LOGO',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
            'MAIL_MAILER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
        ];
        if (! $request->routeIs('install.*')) {
            $envFilePath = base_path('.env');
            $envFile = fopen($envFilePath, 'r');
            while (($line = fgets($envFile)) !== false) {
                if (strpos($line, '=') !== false) {
                    [$variable, $valor] = explode('=', $line, 2);
                    $variable = trim($variable);
                    $valor = trim($valor);
                    if (in_array($variable, $envRequired)) {
                        if (! $valor) {
                            // $install = true;
                            return Redirect::route('install.general.index');
                        }
                    }
                }
            }
            fclose($envFile);
        }

        // if($request->routeIs('install.*') && $install === false):
        //     if(Route::has('web.home.index')):
        //         return Redirect::route('web.home.index');
        //     endif;
        //     if(Route::has('ecommerce.home.index')):
        //         return Redirect::route('ecommerce.home.index');
        //     endif;
        //     if(Route::has('elearning.home.index')):
        //         return Redirect::route('elearning.home.index');
        //     endif;
        // endif;
        return $next($request);
    }
}
