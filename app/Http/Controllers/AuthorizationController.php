<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
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

    /**
     * Authorize process instance cancel action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelProcessInstance(ProcessInstance $processInstance)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('cancel', $processInstance)
        ]);
    }

    /**
     * Authorize process instance form claim action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimProcessInstanceForm(ProcessInstanceForm $processInstanceForm)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('claim', $processInstanceForm)
        ]);
    }

    /**
     * Authorize process instance form unclaim action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unclaimProcessInstanceForm(ProcessInstanceForm $processInstanceForm)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('unclaim', $processInstanceForm)
        ]);
    }

    /**
     * Authorize process instance form edit action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProcessInstanceForm(ProcessInstanceForm $processInstanceForm)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('edit', $processInstanceForm)
        ]);
    }

    /**
     * Authorize process instance form submit action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitProcessInstanceForm(ProcessInstanceForm $processInstanceForm)
    {
        return $this->respond([
            'allowed' => auth()->user()->can('submit', $processInstanceForm)
        ]);
    }
}
