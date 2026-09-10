<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::any('{path?}', fn () => response()->json([
                'message' => 'Not Found',
            ], 404))->where('path', '.*');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn () => true);

        $exceptions->respond(function ($response, \Throwable $exception) {
            if ($response->getStatusCode() === 500) {
                $data = [
                    'message' => 'Internal Server Error',
                ];

//                if (config('app.debug')) {
                    $data += [
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTraceAsString(),
                    ];
//                }

                return response()->json($data, 500);
            }

            return $response;
        });
    })->create();
