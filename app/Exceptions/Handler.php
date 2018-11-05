<?php

namespace App\Exceptions;

use App\Camunda\Exceptions\GeneralException as CamundaGeneralException;
use App\Http\Traits\UsesJsonResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException as AuthorizationException;
use Illuminate\Auth\AuthenticationException as AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException as VerifyCsrfToken;
use Illuminate\Validation\ValidationException as ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException as MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;

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
        // Store exception to return more details in "respondWithError" method.
        $this->exception = $exception;

        // Render Rest API errors.
        if (collect($request->getAcceptableContentTypes())->contains('application/json')) {
            // Handle Camunda errors.
            if ($exception instanceof CamundaGeneralException) {
                return $this->respondInternalError("CamundaGeneralException: {$exception->getMessage()}");
            }

            // Handle eloquent model not found.
            if ($exception instanceof ModelNotFoundException) {
                return $this->respondNotFound('Resource not found.');
            }

            // Handle invalid rest endpoint.
            if ($exception instanceof NotFoundHttpException) {
                return $this->respondNotFound('Endpoint not found.');
            }

            // Handle method not allowed errors.
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->respondMethodNotAllowed();
            }

            // Handle authorization errors.
            if ($exception instanceof AuthorizationException || is_subclass_of($exception, AuthorizationException::class)) {
                // @todo: Maybe add some sort of logging for forbidden actions.
                return $this->respondForbidden($exception->getMessage());
            }

            // Handle authentication error.
            if ($exception instanceof AuthenticationException) {
                return $this->respondUnauthorize('User is not authenticated.');
            }

            // Handle data validation errors.
            if ($exception instanceof ValidationException) {
                return $this->respondValidationError($exception->errors());
            }

            // Handle CSRF token validation error.
            if ($exception instanceof VerifyCsrfToken) {
                return $this->respondInvalidRequest();
            }

            // Default to internal error response.
            return $this->respondInternalError($exception->getMessage());
        }

        return parent::render($request, $exception);
    }
}
