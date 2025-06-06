<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsBackoffice
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['backoffice_esercizio', 'backoffice_amministrazione'])) {
            abort(403, 'Accesso non autorizzato.');
        }

        return $next($request);
    }
}
