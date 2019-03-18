<?php

namespace App\Repositories;

use App\Models\Project\Project;
use App\Models\State;

class ProjectRepository extends BaseEloquentRepository
{
    protected $model = Project::class;
    protected $relationships = [
        'organizationalUnit', 'organizationalUnit.director',
        'currentProcess.definition', 'currentProcess.state',
        'createdBy', 'updatedBy', 'state',
        'informationSheets'
    ];

    /**
     * Create Project.
     *
     * @param  array $data
     * @return Project
     */
    public function create(array $data)
    {
        $project = $this->model->make($data);
        $project->state_id = State::getIdFromKey('project.new');
        $project->created_by = auth()->user()->id;
        $project->updated_by = auth()->user()->id;
        $project->save();

        return $this->getById($project->id);
    }

    /**
     * Update project.
     *
     * @param  array $data
     * @return Project
     */
    public function update($id, array $data = [])
    {
        $project = $this->getById($id);
        $project->fill($data);
        $project->updated_by = auth()->user()->id;
        $project->save();

        return $project;
    }

}
