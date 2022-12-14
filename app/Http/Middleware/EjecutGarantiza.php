<?php

namespace App\Http\Middleware;

use Closure;

class EjecutGarantiza
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
        if(auth()->user()->rol_id < 4)
            return $next($request);
        redirect('/');
    }
}
