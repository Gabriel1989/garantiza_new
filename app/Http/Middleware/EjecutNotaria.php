<?php

namespace App\Http\Middleware;

use Closure;

class EjecutNotaria
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
        /*
        if(auth()->user()->rol_id >= 4)
            return $next($request);
        redirect('/');
        */

        $allowedRoles = [1, 3, 7]; // roles permitidos
        if(in_array(auth()->user()->rol_id, $allowedRoles)) {
            return $next($request);
        }
        redirect('/');
    }
}