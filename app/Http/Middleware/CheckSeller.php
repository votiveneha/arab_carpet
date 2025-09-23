<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  if (Auth::check() && Auth::user()->user_type == 2) {
        //     return $next($request);
        // }
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->user_type == 2 && $user->is_deleted == 0) {
                return $next($request);
            }
        }
        return redirect('/')->with('error', 'You are not authorized as user.');
    }
}
