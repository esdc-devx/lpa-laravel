<?php

namespace App\Models\Traits;

use App\Models\User\User;

/**
 * Add createdBy and updatedBy user relationships to the model.
 */
trait UsesUserAudit
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function updateAudit()
    {
        // Update updatedBy and ensure that timestamp is updated.
        $this->updatedBy()->associate(auth()->user());
        $this->save();
        $this->touch();

        return $this;
    }
}
