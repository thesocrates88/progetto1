<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BackofficeEsercizio
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'backoffice_esercizio') {
            abort(403, 'Accesso non autorizzato.');
        }

        return $next($request);
    }
}

