<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        $guard = $guards[0] ?? null;

        if (! $request->expectsJson()) {

            switch ($guard) {
                case 'customer':
                    $login = route('customer.login');
                    break;

                case 'web':
                default:
                    $login = route('admin.login');
                    break;
            }

            throw new AuthenticationException(
                'Unauthenticated.',
                $guards,
                $login
            );
        }

        throw $exception;
    }
}
