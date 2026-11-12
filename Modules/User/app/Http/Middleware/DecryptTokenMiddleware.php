<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Modules\User\Models\User;

class DecryptTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Authorization')){
            $token=str_replace('Bearer','',$request->header('authorization'));
            $decryptedToken=Crypt::decryptString($token);
            $request->headers->set('authorization',"Bearer {$decryptedToken}");
        }

        return $next($request);
    }
}
