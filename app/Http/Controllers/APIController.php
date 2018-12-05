<?php

namespace App\Http\Controllers;

use App\Http\Traits\UsesJsonResponse;
use Illuminate\Http\Response;

class APIController extends Controller
{
    use UsesJsonResponse;

    const ITEMS_PER_PAGE = 20;

    /**
     * Return a successful response with an empty body.
     *
     * @return Response
     */
    protected function respondNoContent()
    {
        return response()->make('', Response::HTTP_NO_CONTENT);
    }
}
