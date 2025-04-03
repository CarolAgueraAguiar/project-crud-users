<?php

namespace Support\Middlewares;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        //NOTE - Força o Accept header para application/json
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
