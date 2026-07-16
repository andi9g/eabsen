<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\aksesM;

class GerbangAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user->akses()->exists()) {

            $user->akses()->create([
                'akses' => aksesM::count() === 0 ? 'superadmin' : 'user',
            ]);

            $user->load('akses');
        }

        
        return $next($request);
    }
}
