<?php

namespace App\Http\Middleware;

use Closure;

class OperatorCodeAddMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $data = $request->all();
        $data['approval_operator_code'] = $request->user()->operator_code;

        $request->merge($data);
        return $next($request);
    }
}
