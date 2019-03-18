<?php

namespace App\Http\Controllers;

use App\Models\LearningProduct\LearningProductCode;
use Illuminate\Http\Request;

class LearningProductCodeController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respond(
            LearningProductCode::all()
        );
    }
}
