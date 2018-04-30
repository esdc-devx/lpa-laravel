<?php

namespace App\Models\Project;

use App\Models\OrganizationalUnit\OrganizationalUnit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['organizational_unit_id', 'state_id'];
    protected $dates = ['deleted_at'];

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function state()
    {
        return $this->belongsTo(ProjectState::class);
    }
}
