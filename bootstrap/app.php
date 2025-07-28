<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Session;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (){
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {      
        $middleware->prepend(\Illuminate\Session\Middleware\StartSession::class);
        $middleware->append(\App\Http\Middleware\SetLocale::class);
         $middleware->append(\App\Http\Middleware\ShareData::class);
        $middleware->encryptCookies(
            ['locale']
        );
        $middleware->append(\App\Http\Middleware\InitializeSession::class);
        

        $middleware->alias([
            'admin' => CheckAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
