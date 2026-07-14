<?php

use App\Http\Middleware\AddressMiddleware;
use App\Http\Middleware\CartMiddleware;
use App\Http\Middleware\CurrencyMiddleware;
use App\Http\Middleware\InstallMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\OrderMiddleware;
use App\Http\Middleware\PanelMiddleware;
use App\Http\Middleware\SubscribedMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function (Illuminate\Routing\Router $router) { 
            $router->middleware('web')->name('web.')->group(base_path('routes/web.php'));
            $router->middleware(['web', 'ecommerce'])->prefix('ecommerce')->name('ecommerce.')->group(base_path('routes/ecommerce.php'));
            $router->middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));
            $router->middleware('web')->group(base_path('routes/auth.php'));
        },
        commands: base_path('routes/console.php'),
        channels: base_path('routes/channels.php'),
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: ['webhook/*']);
        $middleware->appendToGroup('web', [LanguageMiddleware::class]);
        $middleware->group('admin', [CurrencyMiddleware::class, PanelMiddleware::class]);
        $middleware->group('ecommerce', [CurrencyMiddleware::class, CartMiddleware::class]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'panel' => PanelMiddleware::class,
            'language' => LanguageMiddleware::class,
            'currency' => CurrencyMiddleware::class,
            'user' => UserMiddleware::class,
            'order' => OrderMiddleware::class,
            'address' => AddressMiddleware::class,
            'cart' => CartMiddleware::class,
            'subscribed' => SubscribedMiddleware::class,
            'install' => InstallMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->create();
