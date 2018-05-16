<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessDefinition;
use App\Models\Project\Project;

class AuthorizationController extends APIController
{
    /**
     * Authorize project creation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProject()
    {
        return $this->respond([
            'allowed' => auth()->user()->can('create', Project::class)
        ]);
    }

    /**
     * Authorize project edition.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProject(Project $project)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('update', $project)
        ]);
    }

    /**
     * Authorize project deletion.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProject(Project $project)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('delete', $project)
        ]);
    }

    /**
     * Authorize project process initiation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function startProjectProcess(Project $project, ProcessDefinition $processDefinition)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('start-process', [$project, $processDefinition])
        ]);
    }
}
