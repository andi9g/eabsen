<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'BuatAkses' => App\Http\Middleware\GerbangAkses::class,
            'superadmin' => App\Http\Middleware\GerbangSuperadmin::class,
            'admin' => App\Http\Middleware\GerbangAdmin::class,
            'PasswordDefault' => App\Http\Middleware\GerbangPasswordDefault::class,
            'sessionku' => App\Http\Middleware\GerbangSession::class,
            'Semester' => App\Http\Middleware\GerbangSemester::class,
            'AksesPegawai' => App\Http\Middleware\AksesPegawai::class,
            'pegawai' => App\Http\Middleware\GerbangPegawai::class,
            'wakadankepsek' => App\Http\Middleware\GerbangWaka::class,
            
        ]);
        $middleware->web([
            App\Http\Middleware\GerbangSessionSociallite::class,
        ]);
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
