<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GerbangPasswordDefault
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if($user->is_default_password == 1) {
            return redirect('settings/security')->with("warning", "Silahkan update password Anda terlebih dahulu untuk meningkatkan keamanan akun Anda.");
        }
        return $next($request);
    }
}
