<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User\User;
use App\Models\OrganizationalUnit\OrganizationalUnit;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['owner_id', 'organizational_unit_id'];
    protected $dates = ['deleted_at'];

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
