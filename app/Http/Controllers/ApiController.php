<?php

namespace App\Http\Controllers;

use App\Traits\UsesJsonResponse;

class ApiController extends Controller
{
    use UsesJsonResponse;

    const ITEMS_PER_PAGE = 20;
}
