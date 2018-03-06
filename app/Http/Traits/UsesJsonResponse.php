<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait UsesJsonResponse
{
    protected $statusCode = Response::HTTP_OK;

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Return API response with default format.
     *
     * @param array $data
     * @param array $meta
     * @return Illuminate\Http\Response
     */
    protected function respond($data = [], $meta = [])
    {
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
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondWithError($errors = [])
    {
        return response()->json([
            'errors' => $errors, // @todo: Should return an array of error objects.
        ], $this->statusCode);
    }

    /**
     * Return API response with 404 status code.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondNotFound($message = 'Data not found.')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    /**
     * Return API response with 401 status code.
     * Used when a user is not logged into the system.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondUnauthorize($message = 'Unauthorized.')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->respondWithError($message);
    }

    /**
     * Return API response with 403 status code.
     * Used when a user tries to do an action with insufficient priviledges.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondForbidden($message = 'Insufficient priviledges.')
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->respondWithError($message);
    }

    /**
     * Return API response with 400 status code.
     * @note: Currently not being used, maybe remove at some point?
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondInvalidRequest($message = 'Invalid request.')
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    /**
     * Return API response with 405 status code.
     * Used when the http method used on a route is unsupported.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondMethodNotAllowed($message = 'Method not allowed.')
    {
        return $this->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
            ->respondWithError($message);
    }

    /**
     * Return API response with 422 status code.
     * Used when there are some data validation errors.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondValidationError($errors)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->respondWithError($errors);
    }

    /**
     * Return API response with 500 status code.
     * Used for internal server errors.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondInternalError($message = 'An error occured.')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }
}
