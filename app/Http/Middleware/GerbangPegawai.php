<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GerbangPegawai
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $akses = auth()->user()->akses->akses;
        // dd($akses);
        if($akses == "admin" || $akses == "pegawai" || $akses == "kepsek" || $akses == "waka" || $akses == "tu"){
            return $next($request);
        }else {
            return redirect()->route("dashboard")->with("error", "Anda tidak memiliki akses ke halaman ini.");
        }
    }
}
