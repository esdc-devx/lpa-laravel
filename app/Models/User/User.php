<?php

namespace App\Models\User;

use Laravel\Passport\HasApiTokens;
use Adldap\Laravel\Traits\HasLdapUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\OrganizationUnit\OrganizationUnit;
use App\Models\Project\Project;

class User extends Authenticatable
{
    use Notifiable, HasLdapUser, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'username', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function organizationUnits()
    {
        return $this->belongsToMany(OrganizationUnit::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }
}
