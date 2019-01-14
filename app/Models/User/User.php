<?php

namespace App\Models\User;

use Adldap\Laravel\Traits\HasLdapUser;
use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Project\Project;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Notifiable, HasLdapUser, HasApiTokens, SoftDeletes, Authenticatable, Authorizable;

    protected $fillable = [
       'username',
       'first_name',
       'last_name',
       'email',
       'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $appends = [
        'deleted',
        'name',
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\UserCreated::class,
        'saved'   => \App\Events\UserSaved::class,
    ];

    protected $relationships = [
        'organizationalUnits',
        'roles',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function organizationalUnits()
    {
        return $this->belongsToMany(OrganizationalUnit::class);
    }

    public function processInstanceForms()
    {
        return $this->hasMany(ProcessInstanceForm::class, 'current_editor');
    }

    public function getNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function getDeletedAttribute()
    {
        return $this->deleted_at !== null;
    }

    /**
     * Verify if user has specified role.
     *
     * @param  string $role
     * @return boolean
     */
    public function hasRole(string $role)
    {
        return $this->roles->where('name_key', $role)->first() !== null;
    }

    /**
     * Verify if user has admin role.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Verify whether or not a user belongs to a specified organizational unit.
     *
     * @param  mixed $organizationalUnit
     * @return mixed
     */
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

    /**
     * Return system admin account.
     *
     * @return User
     */
    public static function admin()
    {
        return (new static)->whereUsername(config('auth.admin.username'))->firstOrFail();
    }
}
