<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GerbangSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!($request->session()->has('idinstansi'))) {
            $request->session()->put('idinstansi', auth()->user()->detailuser->instansi->idinstansi??"");
        }

        if(!($request->session()->has('semester'))) {
            // dd(auth()->user()->detailuser->instansi->semesteraktif->semester->idsemester??"");
            $request->session()->put('semester', auth()->user()->detailuser->instansi->semesteraktif->semester->idsemester??"");
            if(empty($request->session()->get('semester')) ) {
                return redirect('dashboard')->with("error", "Silahkan meminta admin sekolah untuk menambahkan semester terlebih dahulu!");
            }
        }
        
        return $next($request);
    }
}
