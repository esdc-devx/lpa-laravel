<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Models\Project\Project;
use App\Repositories\OrganizationalUnitRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

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
        return $this->respond([
            'projects' => $this->projects->with(['state', 'organizationalUnit', 'currentProcess.definition', 'updatedBy'])->getAll()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        return $this->respond([
            'organizational_units' => $this->organizationalUnits->getOwnersFor(auth()->user())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProjectFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectFormRequest $request)
    {
        // Retrieve only the necessary attributes from the request.
        $data = $request->only([
            'name',
            'organizational_unit_id',
            'outline',
        ]);

        return $this->respond(
            $this->projects->create($data)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->respond([
            'project' => $this->projects->with('all')->getById($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = $this->projects->getById($id);
        $this->authorize('update', $project);

        return $this->respond([
            'project'              => $project,
            'organizational_units' => $this->organizationalUnits->getOwnersFor(auth()->user())
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProjectFormRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectFormRequest $request, $id)
    {
        // Ensure that user cannot update any other attributes.
        $data = $request->only([
            'name',
            'organizational_unit_id',
            'outline',
        ]);

        return $this->respond(
            $this->projects->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        return $this->respond([
            'deleted' => $project->delete(),
        ]);
    }
}
