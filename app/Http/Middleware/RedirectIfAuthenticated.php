<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (Auth::guard($guard)->check()) {
//            return redirect('/admin');
//        }
        if (Auth::guard($guard)->check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                switch ($guard) {
                    case 'admin':
                        $path = '/admin';
                        break;

                    default:
                        $path = '/user';
                        break;
                }

                return redirect($path);
            }
        }

        return $next($request);
    }
}
