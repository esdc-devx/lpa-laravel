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
        ]);

        // Filter on specific project.
        if ($projectId = request()->get('project_id')) {
            $learningProducts->where('project_id', $projectId);
        }

        return $this->respond([
            'learning_products' => $learningProducts->get(),
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
     * @param  LearningProduct  $learningProduct
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
     * @param  LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(LearningProduct $learningProduct)
    {
        $this->authorize('update', $learningProduct);

        // Load all the required relationship for edition.
        $learningProduct->load(['organizationalUnit', 'manager', 'primaryContact']);

        // Get learning product owners organizational unit for user.
        $organizationalUnits = OrganizationalUnit::getLearningProductOwnersFor(auth()->user());

        return $this->respond([
            'learning_product'     => $learningProduct,
            'organizational_units' => $organizationalUnits,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LearningProductFormRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(LearningProductFormRequest $request, $id)
    {
        // Ensure that user cannot update any other attributes.
        $data = $request->only([
            'name',
            'organizational_unit_id',
            'manager',
            'primary_contact',
        ]);

        return $this->respond(
            $this->learningProducts->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LearningProduct  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(LearningProduct $learningProduct)
    {
        $this->authorize('delete', $learningProduct);

        return $this->respond([
            'deleted' => $learningProduct->delete(),
        ]);
    }
}
