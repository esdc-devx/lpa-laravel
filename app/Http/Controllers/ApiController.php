<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;

class ApiController extends Controller
{
    const ITEM_PER_PAGE = 20;
    protected $statusCode = Response::HTTP_OK;

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respond($data = [], $message = null)
    {
        return response([
            'message' => $message,
            'data' => $data
        ], $this->statusCode);
    }

    protected function respondWithError($errorMessage)
    {
        return response([
            'error' => $errorMessage,
        ], $this->statusCode);
    }

    protected function respondNotFound($message = 'Data not found.')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    protected function respondInvalidRequest($message = 'Invalid request.')
    {
        //Response::HTTP_UNPROCESSABLE_ENTITY
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError($message);
    }

    protected function respondInternalError($message = 'An error occured.')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message);
    }

}
