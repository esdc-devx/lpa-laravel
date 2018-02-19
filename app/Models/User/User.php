<?php

namespace App\Models\User;

use Laravel\Passport\HasApiTokens;
use Adldap\Laravel\Traits\HasLdapUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\OrganizationUnit\OrganizationUnit;
use App\Models\Project\Project;

class User extends Authenticatable
{
    use Notifiable, HasLdapUser, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'username', 'first_name', 'last_name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $appends = ['deleted', 'name'];

    public function getNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function getDeletedAttribute()
    {
        return $this->deleted_at !== null;
    }

    public function organizationUnits()
    {
        return $this->belongsToMany(OrganizationUnit::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }
}
