<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyMiddleware
{
    const API_KEY = 'RSAy430_a3eGR'; //should be stored as environment Variable

    public function handle($request, Closure $next)
    {
        if($request->input('api_key') !== self::API_KEY) {
            die('API_KEY invalid');
        }
        return $next($request);
    }
}
