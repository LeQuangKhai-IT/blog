<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('An error occurred: ' . $e->getMessage());
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof UnauthorizedHttpException) {
                return response()->json(['error' => $e->getMessage()], 401);
            } elseif ($e instanceof AccessDeniedHttpException) {
                return response()->json(['error' => $e->getMessage()], 403);
            } elseif ($e instanceof BadRequestHttpException) {
                return response()->json(['error' => $e->getMessage()], 400);
            } elseif ($e instanceof NotFoundHttpException) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        });
    }

    protected function shouldReturnJson($request, Throwable $e)
    {
        return true;
    }
}
