<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Return entity type string from model class name.
     *
     * @return string
     */
    public static function getEntityType()
    {
        return kebab_case(class_basename(static::class));
    }
}
