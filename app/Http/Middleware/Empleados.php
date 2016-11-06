<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class Empleados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$cargo)
    {
        if(Auth::check()){     
            if ($request->user()->empleado->tienePermisos($cargo))
                return $next($request);
            else
                return  abort(403,"No tienes los suficientes permisos para entrar a esta áreasadsa");
        }
        else{
            return  abort(403,"No tienes los suficientes permisos para entrar a esta áreaasdsa  ");
        }
    }
}
