<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {});

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = $this->getStatusCode($e);

                // this is for the query exception
                if ($e instanceof QueryException) {
                    return response()->json([
                        'success' => false,
                        'message' => $this->getGenericErrorMessage($e),
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                return response()->json([
                    'success' => false,
                    'message' => $this->getGenericErrorMessage($e),
                ], $statusCode);
            }
        });
    }


    /**
     * Determine the HTTP status code based on the exception.
     */
    private function getStatusCode(Throwable $e): int
    {
        if ($e instanceof ValidationException) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        } elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            return Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return Response::HTTP_METHOD_NOT_ALLOWED;
        } elseif ($e instanceof AuthenticationException) {
            return Response::HTTP_UNAUTHORIZED;
        } elseif ($e instanceof AuthorizationException) {
            return Response::HTTP_FORBIDDEN;
        } elseif ($e instanceof BadRequestHttpException) {
            return Response::HTTP_BAD_REQUEST;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Provide a generic error message.
     */
    private function getGenericErrorMessage(Throwable $e): string
    {
        if ($e instanceof ValidationException) {
            return 'The given data was invalid.';
        } elseif ($e instanceof AuthenticationException) {
            return 'Unauthenticated.';
        } elseif ($e instanceof AuthorizationException) {
            return 'This action is unauthorized.';
        } elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            return 'Resource not found.';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return 'Method not allowed.';
        } elseif ($e instanceof QueryException) {
            return 'A database error occurred. Please try again later.'; // Generic DB error message
        }

        // General fallback message
        return 'An error occurred. Please try again later.';
    }
}
