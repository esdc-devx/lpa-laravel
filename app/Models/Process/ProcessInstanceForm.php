<?php

namespace App\Models\Process;

use App\Events\ProcessInstanceFormClaimed;
use App\Events\ProcessInstanceFormUnclaimed;
use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;

class ProcessInstanceForm extends BaseModel
{
    use UsesUserAudit;

    protected $hidden = ['process_form_id', 'process_instance_id', 'state_id', 'process_instance_step_id', 'organizational_unit_id', 'created_at', 'created_by'];

    public function definition()
    {
        return $this->belongsTo(ProcessForm::class, 'process_form_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currentEditor()
    {
        return $this->belongsTo(User::class, 'current_editor');
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function step()
    {
        return $this->belongsTo(ProcessInstanceStep::class, 'process_instance_step_id');
    }

    /**
     * Assign user to be able to edit the form.
     *
     * @param  User $user
     * @return $this
     */
    public function claim(User $user = null)
    {
        $user = $user ?? auth()->user();
        $this->currentEditor()->associate($user);
        $this->save(['timestamps' => false]);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormClaimed($user, $this));

        return $this;
    }

    /**
     * Remove current form editor.
     *
     * @return $this
     */
    public function unclaim()
    {
        $this->currentEditor()->dissociate();
        $this->save(['timestamps' => false]);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormUnclaimed($this));

        return $this;
    }

    /**
     * Assign user to be able to edit the form.
     *
     * @param  User $user
     * @return $this
     */
    public function claim(User $user = null)
    {
        $user = $user ?? auth()->user();
        $this->currentEditor()->associate($user);
        $this->save(['timestamps' => false]);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormClaimed($user, $this));

        return $this;
    }

    /**
     * Remove current form editor.
     *
     * @return $this
     */
    public function unclaim()
    {
        $this->currentEditor()->dissociate();
        $this->save(['timestamps' => false]);

        // Dispatch event for CamundaEventSubscriber to respond for.
        event(new ProcessInstanceFormUnclaimed($this));

        return $this;
    }
}
