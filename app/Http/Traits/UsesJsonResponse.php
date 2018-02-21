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
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondForbidden($message = 'Forbidden.')
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->respondWithError($message);
    }

    /**
     * Return API response with 400 status code.
     *
     * @param array $errors
     * @return Illuminate\Http\Response
     */
    protected function respondInvalidRequest($message = 'Invalid request.')
    {
        // @note: Response::HTTP_UNPROCESSABLE_ENTITY
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    /**
     * Return API response with 500 status code.
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
