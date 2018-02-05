<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;
use App\Models\OrganizationUnit\OrganizationUnitRepository;

class ProjectController extends ApiController
{
    protected $organizationUnits;

    public function __construct(OrganizationUnitRepository $organizationUnits)
    {
        $this->organizationUnits = $organizationUnits;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request()->get('limit') ?: self::ITEMS_PER_PAGE;
        $paginator = Project::with('owner', 'organizationUnit')
        ->orderBy('name', 'asc')
        ->paginate($limit);

        return $this->respond([
            'paginator' => json_decode($paginator->toJson())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->respond([
            'organization_units' => $this->organizationUnits->getOwners()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Response::HTTP_CREATED
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->respond([
            'project' => Project::with('owner', 'organizationUnit')->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
    }
}
