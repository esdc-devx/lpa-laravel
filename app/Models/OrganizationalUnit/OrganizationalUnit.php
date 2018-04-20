<?php

namespace App\Models\OrganizationalUnit;

use App\Models\User\User;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    use Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'pivot', 'created_at', 'updated_at'];

    public $translatedAttributes = ['name', 'acronym'];

    public function director()
    {
        return $this->belongsTo(User::class, 'director');
    }
}
