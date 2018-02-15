<?php

namespace App\Models\OrganizationUnit;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OrganizationUnit extends Model
{
    use Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'pivot', 'created_at', 'updated_at'];

    public $translatedAttributes = ['name', 'acronym'];
}
