<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningProductFormRequest;
use App\Models\LearningProduct\LearningProduct;
use App\Models\OrganizationalUnit;
use App\Models\Project\Project;
use App\Repositories\LearningProductRepository;
use Illuminate\Http\Request;

class LearningProductController extends APIController
{
    protected $learningProducts;

    public function __construct(LearningProductRepository $learningProducts)
    {
        $this->learningProducts = $learningProducts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $learningProducts = LearningProduct::with([
            'type', 'subType', 'state', 'organizationalUnit', 'currentProcess.definition'
        ])->get();

        return $this->respond([
            'learning_products' => $learningProducts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', LearningProduct::class);

        // Get learning product owners organizational unit for user.
        $organizationalUnits = OrganizationalUnit::getLearningProductOwnersFor(auth()->user());

        // Fetch projects available for learning product creation.
        $projects = Project::availableForLearningProductCreation()
            ->map(function ($project) {
                return $project->only(['id', 'name', 'organizational_unit_id', 'available_learning_product_types']);
            })->values();

        return $this->respond([
            'projects'             => $projects,
            'organizational_units' => $organizationalUnits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LearningProductFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LearningProductFormRequest $request)
    {
        return $this->respond(
            $this->learningProducts->create($request->all())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LearningProduct\LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function show(LearningProduct $learningProduct)
    {
        // Load all the required relationship for display.
        $learningProduct->load([
            'type', 'subType', 'state', 'organizationalUnit', 'currentProcess.definition', 'currentProcess.state',
            'primaryContact', 'manager', 'createdBy', 'updatedBy',
        ]);

        return $this->respond([
            'learning_product' => $learningProduct,
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
