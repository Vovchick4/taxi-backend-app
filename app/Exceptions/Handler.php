<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (Throwable $e) {
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.page_not_found'),
                ], 404);
            }
        });
    }

    protected function invalidJson($request, $exception)
    {
        return response()->json([
            'data' => [],
            'message' => 'The given data was invalid',
            'errors' => $exception->errors(),
            'status' => $exception->status
        ], $exception->status);
    }
}
