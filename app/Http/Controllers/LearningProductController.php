<?php

namespace App\Http\Controllers;

use App\Models\LearningProduct\LearningProduct;
use Illuminate\Http\Request;

class LearningProductController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respond([
            'learning_products' => LearningProduct::with([
                'type', 'subType', 'state', 'organizationalUnit', 'currentProcess.definition'
            ])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LearningProduct\LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function show(LearningProduct $learningProduct)
    {
        return $this->respond([
            'learning_product' => $learningProduct->load([
                'type', 'subType', 'state', 'organizationalUnit', 'currentProcess.definition', 'currentProcess.state',
                'primaryContact', 'manager', 'createdBy', 'updatedBy',
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LearningProduct\LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(LearningProduct $learningProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LearningProduct\LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LearningProduct $learningProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LearningProduct\LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(LearningProduct $learningProduct)
    {
        //
    }
}
