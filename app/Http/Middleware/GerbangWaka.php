<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GerbangWaka
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user()->akses->akses ?? "user";
        if($user == "kepsek" || $user == "waka" || $user == "admin" || $user == "pegawai"|| $user == "tu") {
            return $next($request);
        }else {
            return redirect('dashboard')->with("error", "Terjadi kesalahan");
        }
    }
}
