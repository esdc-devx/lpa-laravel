<?php

namespace App\Models;

use App\Models\Traits\UsesKeyNameField;

class ListableModel extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'parent_id',
        'name_key',
        'name_en',
        'name_fr',
        'active',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected $localizable = [
        'name',
    ];

    protected $parent = 'parent_id';

    public $timestamps = false;

    public static function toListArray()
    {
        return nestable(
            static::where('active', 1)->get()->toArray()
        )->renderAsArray();
    }
}
