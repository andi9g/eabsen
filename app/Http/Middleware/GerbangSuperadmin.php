<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Flux\Flux;

class GerbangSuperadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if($user->akses->akses == 'superadmin') {
            return $next($request);
        }else {
            return redirect('dashboard')->with("error", "Maaf, Anda tidak memiliki akses ke halaman tersebut.");
        }
    }
}
