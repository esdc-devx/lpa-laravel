<?php

namespace App\Models\User;

use Adldap\Laravel\Traits\HasLdapUser;
use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\Project\Project;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Notifiable, HasLdapUser, HasApiTokens, SoftDeletes, Authenticatable, Authorizable;

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
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are added to the model through getters.
     *
     * @var array
     */
    protected $appends = ['deleted', 'name'];

    /**
     * The events that are dispatched by the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\UserCreated::class
    ];

    public function getNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function getDeletedAttribute()
    {
        return $this->deleted_at !== null;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function organizationalUnits()
    {
        return $this->belongsToMany(OrganizationalUnit::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function hasRole(string $role)
    {
        return $this->roles->where('name_key', $role)->first() !== null;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function belongsToOrganizationalUnit($organizationalUnit)
    {
        if (is_numeric($organizationalUnit)) {
            return $this->organizationalUnits->firstWhere('id', $organizationalUnit) !== null;
        }

        if ($organizationalUnit instanceof OrganizationalUnit) {
            return $this->organizationalUnits->firstWhere('id', $organizationalUnit->id) !== null;
        }

        return false;
    }
}
