<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;
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
            'projects' => $this->projects->with(['state', 'organizationalUnit', 'currentProcess.definition'])->getAll()
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
            'organizational_unit'
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
            'project'   => $this->projects->with('all')->getById($id),
            'processes' => ProcessDefinition::where('entity_type', 'project')->get(),
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
        $project = $this->projects->with('organizationalUnit')->getById($id);
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
            'organizational_unit',
        ]);

        return $this->respond(
            $this->projects->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->projects->getById($id));

        return $this->respond(
            $this->projects->delete($id)
        );
    }

    /**
     * Start a process instance.
     *
     * @param  int $id
     * @param  ProcessDefinition $processDefinition
     * @return \Illuminate\Http\Response
     */
    public function startProcessInstance($id, ProcessDefinition $processDefinition)
    {
        $project = $this->projects->getById($id);
        $this->authorize('start-process', [$project, $processDefinition]);

        return $this->respond([
            'process_instance' => \Process::startProcessInstance($processDefinition, $project)
        ]);
    }

    /**
     * Display the process instance.
     *
     * @param  int $projectId
     * @param  int $processInstanceId
     * @return \Illuminate\Http\Response
     */
    public function showProcessInstance($projectId, $processInstanceId)
    {
        return $this->respond([
            'process_instance' => ProcessInstance::withProcessDetails()
                ->where([
                    'id' => $processInstanceId,
                    'entity_type' => 'project',
                    'entity_id' => $projectId,
                ])->firstOrFail()
        ]);
    }

}
