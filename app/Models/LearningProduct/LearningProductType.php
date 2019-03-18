<?php

namespace App\Models\LearningProduct;

use App\Models\ListableModel;

class LearningProductType extends ListableModel
{
    public function scopeSubTypes($query)
    {
        $query->where('parent_id', '!=', 0);
    }

    /**
     * Retrieve all sub types for a learning product type.
     *
     * @param  string $typeKey
     * @return \Illuminate\Support\Collection
     */
    public static function getSubTypesFor($typeKey)
    {
        static $parent;
        static $subTypes;

        if (! $parent || $parent->name_key !== $typeKey) {
            $parent = static::whereNameKey($typeKey)->firstOrFail();
            $subTypes = static::whereParentId($parent->id)->get();
        }

        return $subTypes;
    }
}
