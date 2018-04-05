<?php

namespace App\Models\User;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'pivot'];

    public $timestamps = false;
    public $translatedAttributes = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
