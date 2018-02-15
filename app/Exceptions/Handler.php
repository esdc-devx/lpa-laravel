<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Camunda\Exceptions\GeneralException as CamundaGeneralException;
use Illuminate\Auth\AuthenticationException as AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;
use App\Traits\UsesJsonResponse;

class Handler extends ExceptionHandler
{
    use UsesJsonResponse;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Render Rest API errors.
        if ($request->wantsJson()) {

            // Handle Camunda errors.
            if ($exception instanceof CamundaGeneralException) {
                return $this->respondWithError($exception->getMessage());
            }

            // Handle eloquent model not found.
            if ($exception instanceof ModelNotFoundException) {
                return $this->respondNotFound('Resource not found.');
            }

            // Handle invalid rest endpoint.
            if ($exception instanceof NotFoundHttpException) {
                return $this->respondNotFound('Endpoint not found.');
            }

            // Handle authentication error.
            if ($exception instanceof AuthenticationException) {
                return $this->respondUnauthorize('User is not authenticated.');
            }

            // Default to internal error response.
            $error = $exception->getMessage();

            // Add debug information when debug mode is on.
            if (config('app.debug')) {
                $exceptionClass = get_class($exception);
                $file = $exception->getFile();
                $line = $exception->getLine();

                $error .= PHP_EOL . "Exception: $exceptionClass";
                $error .= PHP_EOL . "File: $file";
                $error .= PHP_EOL . "Line: $line";
            }
            return $this->respondInternalError($error);
        }

        return parent::render($request, $exception);
    }
}
