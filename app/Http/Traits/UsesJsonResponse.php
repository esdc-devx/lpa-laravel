<?php

namespace App\Http\Traits;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Response;

trait UsesJsonResponse
{
    protected $exception;
    protected $statusCode = Response::HTTP_OK;

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Return API response with default format.
     *
     * @param mixed $data | array or BaseResource
     * @param array $meta
     * @return Illuminate\Http\Response
     */
    protected function respond($data, $meta = [])
    {
        // If data is of type BaseResource, defer to its response format.
        // @note: Consider using resources for all api response eventually?
        if ($data instanceof BaseResource) {
            return $data->response()->setStatusCode($this->statusCode);
        }

        // Default json format response.
        return response()->json([
            'data' => $data,
            'meta' => $meta
        ], $this->statusCode);
    }

    /**
     * Return API response with pagination format.
     *
     * @param Illuminate\Pagination\LengthAwarePaginator $pagination
     * @return Illuminate\Http\Response
     */
    protected function respondWithPagination($pagination)
    {
        return response()->json([
            'data' => $pagination->items(),
            'meta' => [
                'total' => $pagination->total(),
                'per_page' => $pagination->perPage()
            ]
        ], $this->statusCode);
    }

    /**
     * Return API response with error format.
     *
     * @param array $response
     * @return Illuminate\Http\Response
     */
    protected function respondWithError($response = [])
    {
        // Add information about last thrown exception.
        if ($this->exception) {
            $response['type'] = get_class($this->exception);
            $response['message'] = $response['message'] ?? $this->exception->getMessage();

            // Add debug information when debug mode is on.
            if (config('app.debug')) {
                $debug = PHP_EOL . 'Exception: ' . get_class($this->exception);
                $debug .= PHP_EOL . 'File: ' . $this->exception->getFile();
                $debug .= PHP_EOL . 'Line: ' . $this->exception->getLine();
                $response['debug'] = $debug;
            }
        }

        return response()->json($response, $this->statusCode);
    }

    /**
     * Return API response with 404 status code.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondNotFound($message = 'Resource not found')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError([
                'message' => $message,
            ]);
    }

    /**
     * Return API response with 401 status code.
     * Used when a user is not logged into the system.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondUnauthorize($message = 'Unauthorized')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->respondWithError([
                'message' => $message,
            ]);
    }

    /**
     * Return API response with 403 status code.
     * Used when a user tries to do an action with insufficient priviledges.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondForbidden($message = 'Insufficient priviledges')
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->respondWithError([
                'message' => $message,
            ]);
    }

    /**
     * Return API response with 400 status code.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondInvalidRequest($message = 'HTTP Bad request')
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError([
                'message' => $message,
            ]);
    }

    /**
     * Return API response with 405 status code.
     * Used when the http method used on a route is unsupported.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondMethodNotAllowed($message = 'HTTP Method not allowed')
    {
        return $this->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
            ->respondWithError([
                'message' => $message,
            ]);
    }

    /**
     * Return API response with 422 status code.
     * Used when there are some data validation errors.
     *
     * @param  array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondValidationError($errors)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->respondWithError([
                'errors' => $errors,
            ]);
    }

    /**
     * Return API response with 500 status code.
     * Used for internal server errors.
     *
     * @param  string $message
     * @return Illuminate\Http\Response
     */
    protected function respondInternalError($message = 'An internal server error occured')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError([
                'message' => $message,
            ]);
    }
}
