<?php

namespace App\Listeners;

use App\Models\Project\Project;
use App\Models\State;

class LearningProductEventSubscriber
{
    protected $project;

    /**
     * Handle LearningProductCreated events.
     */
    public function onLearningProductCreated($event)
    {
        $this->updateProjectState($event);
    }

    /**
     * Handle LearningProductUpdated events.
     */
    public function onLearningProductUpdated($event)
    {
        $this->updateProjectState($event);
    }

    /**
     * Handle LearningProductDeleted events.
     */
    public function onLearningProductDeleted($event)
    {
        $this->updateProjectState($event);
    }

    /**
     * Update parent project state.
     *
     * @param  mixed $event
     * @return void
     */
    protected function updateProjectState($event)
    {
        // Course is not currently attached to any project.
        if (! $projectId = $event->learningProduct->project_id ?? null) {
            return;
        }

        $this->project = Project::with('state')->find($projectId);

        // Don't update parent project state when learning product was deleted
        // and that current project state is not active or active full.
        if (class_basename($event) == 'LearningProductDeleted' && ! in_array($this->project->state->name_key, ['active', 'active-full'])) {
            return;
        }

        // Update project state based on current number of learning products.
        $this->project->state()->associate(
            $this->resolveProjectState()
        )->save();
    }

    /**
     * Resolve project state based upon some business logic.
     *
     * @return State
     */
    protected function resolveProjectState()
    {
        $this->project->load(['learningProducts', 'plannedProductList.plannedProducts']);

        // No learning products under the project, update state back to approved.
        if ($this->project->learningProducts->isEmpty()) {
            return State::getByKey('project.approved')->first();
        }

        // Some learning products are created under the project, update state to active.
        if ($this->project->learningProducts->count() < $this->project->plannedProductList->plannedProducts->sum('quantity')) {
            return State::getByKey('project.active')->first();
        }

        // All learning products were created, update state to active full.
        if ($this->project->learningProducts->count() == $this->project->plannedProductList->plannedProducts->sum('quantity')) {
            return State::getByKey('project.active-full')->first();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\LearningProductCreated',
            'App\Listeners\LearningProductEventSubscriber@onLearningProductCreated'
        );

        $events->listen(
            'App\Events\LearningProductUpdated',
            'App\Listeners\LearningProductEventSubscriber@onLearningProductUpdated'
        );

        $events->listen(
            'App\Events\LearningProductDeleted',
            'App\Listeners\LearningProductEventSubscriber@onLearningProductDeleted'
        );
    }

}
