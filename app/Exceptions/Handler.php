<?php

namespace App\Exceptions;

use App\Contracts\Exceptions\CustomException;
use Throwable;
use App\Http\Responses\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return ApiResponse::error([], 'Resource not found', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error(
                $exception->getMessage(),
                'Método HTTP não permitido',
                405
            );
        }

        if ($exception instanceof MethodNotFoundException) {
            return ApiResponse::error(
                $exception->getMessage(),
                'Método HTTP não encontrado',
                405
            );
        }

        if ($exception instanceof ValidationException) {
            return ApiResponse::error(
                $exception->errors(),
                'Parâmetros inválidos',
                422,
                true
            );
        }

        if ($exception instanceof ApiTokenException) {
            return ApiResponse::error(
                $exception->getMessage(),
                'Requisição não autorizada',
                401
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::error(
                [],
                'Not Found',
                404
            );
        }

        if ($exception instanceof CustomException) {
            return ApiResponse::error(
                [],
                $exception->getMessage(),
                422
            );
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return ApiResponse::error(
                env('APP_DEBUG')
                    ? [
                        'message' => $exception->getMessage(),
                        'code'    => $exception->getCode(),
                        'trace'   => $exception->getTrace(),
                    ]
                    : [],
                'Internal Server Error',
                500
            );
        }

        return parent::render($request, $exception);
    }
}
