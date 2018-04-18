<?php

namespace App\Models\Project;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ProjectState extends Model
{
    use Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'pivot'];

    public $timestamps = false;
    public $translatedAttributes = ['name'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function getFromKey(string $key)
    {
        return ProjectState::where('unique_key', $key)->first();
    }
}
