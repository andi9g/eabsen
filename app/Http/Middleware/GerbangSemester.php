<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\semesterM;

class GerbangSemester
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idinstansi = $request->session()->get('idinstansi');
         if(!($request->session()->has('idsemester'))) {
            $request->session()->put('idsemester', auth()->user()->detailuser->instansi->idsemester->idsemester??"");
        }
        
        if(empty($request->session()->get('idsemester')) &&  auth()->user()->akses->akses != "superadmin") {
            return redirect()->route("dashboard")->with("error", "Semester belum diatur, silahkan hubungi admin untuk mengatur semester");
        }
        return $next($request);
    }
}
