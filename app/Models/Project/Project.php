<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User\User;
use App\Models\OrganizationUnit\OrganizationUnit;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['owner_id', 'organization_unit_id'];
    protected $dates = ['deleted_at'];

    public function organizationUnit()
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
