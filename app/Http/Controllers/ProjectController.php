<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\OrganizationalUnitRepository;

class ProjectController extends APIController
{
    protected $projects;
    protected $organizationalUnits;

    public function __construct(ProjectRepository $projects, OrganizationalUnitRepository $organizationalUnits)
    {
        $this->projects = $projects;
        $this->organizationalUnits = $organizationalUnits;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request()->get('limit') ?: self::ITEMS_PER_PAGE;
        $results = $this->projects->getAll($limit);
        return $this->respondWithPagination($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->respond([
            'organizational_units' => $this->organizationalUnits->getOwners()->toArray()
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
            // @todo: Move to Project Repository.
            'project' => Project::with('owner', 'organizationalUnit')->findOrFail($id)
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
