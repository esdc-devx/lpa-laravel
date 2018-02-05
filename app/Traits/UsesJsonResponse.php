<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait UsesJsonResponse
{
    protected $statusCode = Response::HTTP_OK;

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respond($data = [], $message = null)
    {
        return response()->json([
            'data' => $data,
            'meta' => $message
        ], $this->statusCode);
    }

    protected function respondWithError($errors = [])
    {
        return response()->json([
            'errors' => $errors, // @todo: Should return an array of error objects.
        ], $this->statusCode);
    }

    protected function respondNotFound($message = 'Data not found.')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    protected function respondInvalidRequest($message = 'Invalid request.')
    {
        // @note: Response::HTTP_UNPROCESSABLE_ENTITY
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    protected function respondInternalError($message = 'An error occured.')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }

    // @todo: Add respondWithPaginator ?
}
