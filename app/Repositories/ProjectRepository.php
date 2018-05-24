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
        'createdBy', 'updatedBy', 'state'
    ];

    /**
     * Create Project.
     *
     * @param  array $data
     * @return Project
     */
    public function create(array $data)
    {
        $project = $this->model->create([
            'name'                   => $data['name'],
            'organizational_unit_id' => $data['organizational_unit'],
            'state_id'               => State::getByKey('project.new')->first()->id,
            'created_by'             => auth()->user()->id,
            'updated_by'             => auth()->user()->id,
        ]);

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

        // Update name.
        if (isset($data['name'])) {
            $project->name = $data['name'];
        }

        // Update organizational unit.
        if (isset($data['organizational_unit'])) {
            $project->organizational_unit_id = $data['organizational_unit'];
        }

        $project->updated_by = auth()->user()->id;
        $project->save();

        return $project;
    }

}
