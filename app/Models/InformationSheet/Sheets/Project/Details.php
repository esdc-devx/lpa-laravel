<?php

namespace App\Models\InformationSheet\Sheets\Project;

use App\Events\Process\ProjectApprovalCompleted;
use App\Models\InformationSheet\InformationSheet;
use App\Models\Process\ProcessInstanceForm;

class Details
{
    /**
     * Respond to ProjectApprovalCompleted event to create and associate
     * an information sheet to the project.
     *
     * @param  ProjectApprovalCompleted $event
     * @return void
     */
    public function onProjectApprovalCompleted($event)
    {
        // Create details information sheet for project.
        InformationSheet::createFromDefinition(
            $event->processInstance->entity,
            'details'
        );
    }

    /**
     * Return data for project details information sheet.
     *
     * @param  App\Models\Project\Project $project
     * @return array
     */
    public function getData($project)
    {
        // Load project relationships.
        $project->load([
            'organizationalUnit.director',
            'state',
            'createdBy',
            'updatedBy',
            'learningProducts.state',
            'learningProducts.currentProcess.definition',
            'learningProducts.organizationalUnit',
            'learningProducts.primaryContact',
            'learningProducts.manager',
            'learningProducts.createdBy',
            'learningProducts.updatedBy',
        ]);

        // Load business case form.
        $businessCase = ProcessInstanceForm::where([
            'entity_type' => 'business-case',
            'entity_id'   => $project->business_case_id,
        ])->with('formData')->firstOrfail();

        // Load planned product list form.
        $plannedProductList = ProcessInstanceForm::where([
            'entity_type' => 'planned-product-list',
            'entity_id'   => $project->planned_product_list_id,
        ])->with('formData')->firstOrfail();

        return [
            'project'              => $project->toArray(),
            'business_case'        => $businessCase->toArray(),
            'planned_product_list' => $plannedProductList->toArray(),
        ];
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ProjectApprovalCompleted::class,
            static::class . '@onProjectApprovalCompleted'
        );
    }
}
