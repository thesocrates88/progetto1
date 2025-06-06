<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\BackofficeEsercizio; // middlware per il backoffice esercizio
use App\Http\Middleware\BackofficeAmministrativo; // middlware per il backoffice amministrativo
use App\Http\Middleware\EnsureUserIsCustomer; //middlware per il customer
use App\Http\Middleware\EnsureUserIsBackoffice; //middlware per componenti comuni a amministrativo e esercizio




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrazione ALIAS di middleware
        $middleware->alias([
            'backoffice_esercizio' => BackofficeEsercizio::class,
            'backoffice_amministrazione' => BackofficeAmministrativo::class,
            'customer' => EnsureUserIsCustomer::class,
            'backoffice' => EnsureUserIsBackoffice::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
