<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AksesPegawai
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idinstansi = session()->get("idinstansi");
        $pegawai = User::whereHas("detailuser", function ($q) use ($idinstansi) {
            $q->where("idinstansi", $idinstansi);
        })->whereHas("akses", function ($q){
            $q->where("akses", "pegawai");
        })->exists();

        if($pegawai) {
            return $next($request);
        }else {
            return redirect('pegawai')->with("error", "Silahkan menambahkan pegawai terlebih dahulu!");
        }
    }
}
