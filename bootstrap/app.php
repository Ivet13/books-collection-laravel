<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\GetSitemap;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
            'setlocale' => SetLocale::class,
            'sitemap' => GetSitemap::class,
        ]);
        /*

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('customer') || $request->is('customer/*')) {
                return route('customer.login');
            }

            return route('customer.login');
        });*/
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
