<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    protected $hidden = ['owner_id', 'organization_unit_id', 'created_at', 'updated_at'];

    public function organizationUnit()
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
