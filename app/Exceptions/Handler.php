<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    private const ERRORS = [
        AuthenticationException::class => [
            'message' => 'Unauthenticated',
            'code' => Response::HTTP_UNAUTHORIZED,
        ],
        AccessDeniedHttpException::class => [
            'message' => 'Unauthorized',
            'code' => Response::HTTP_FORBIDDEN,
        ],
        NotFoundHttpException::class => [
            'message' => 'Page not found',
            'code' => Response::HTTP_NOT_FOUND,
        ],
        ModelNotFoundException::class => [
            'message' => 'Page not found',
            'code' => Response::HTTP_NOT_FOUND,
        ],
        MethodNotAllowedHttpException::class => [
            'message' => 'Page not found',
            'code' => Response::HTTP_NOT_FOUND,
        ],
        ValidationException::class => [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ],
        ApiException::class => [
            'code' => Response::HTTP_BAD_REQUEST,
        ],
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response
    {
        $class = $exception::class;

        if (array_key_exists($class, self::ERRORS)) {
            $error = new Error(
                self::ERRORS[$class]['message'] ?? $exception->getMessage(),
                self::ERRORS[$class]['code'] ?? 500,
                method_exists($exception, 'errors') ? $exception->errors() : [],
            );
        } else {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            if (config('app.debug') === true) {
                return parent::render($request, $exception);
            }

            $error = new Error();
        }

        return ErrorResource::make($error)
            ->response()
            ->setStatusCode($error->code);
    }
}
