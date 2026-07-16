<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class GerbangSessionSociallite
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !$request->is('register') && 
            !$request->is('auth/google*')
        ) {
            if (Session::has('socialite')) {
                Session::forget('socialite');
            }
        }
        // dd(Session::get('socialite'));

        return $next($request);
    }
}
