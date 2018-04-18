<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Models\Project\Project;
use App\Repositories\OrganizationalUnitRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
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
        $limit = request()->get('limit') ?: self::ITEMS_PER_PAGE;
        return $this->respondWithPagination(
            $this->projects->getPaginated($limit)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Project::class);

        return $this->respond([
            'organizational_units' => $this->organizationalUnits->getOwnersFor(auth()->user())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
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
            'project' => $this->projects->getById($id)
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
            'organizational_unit',
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
    public function destroy(Request $request, $id)
    {
        $this->authorize('delete', $this->projects->getById($id));

        return $this->respond(
            $this->projects->delete($id)
        );
    }
}
