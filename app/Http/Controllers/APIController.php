<?php

namespace App\Http\Controllers;

use App\Http\Traits\UsesJsonResponse;

class APIController extends Controller
{
    use UsesJsonResponse;

    const ITEMS_PER_PAGE = 20;
}
