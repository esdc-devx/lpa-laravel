<?php

namespace App\Models\Traits;

/**
 * Add a query scope accessor by name_key value.
 */
trait UsesKeyNameField
{
    public function scopeGetByKey($query, $key)
    {
        $identifier = explode('.', $key);
        $where = ['name_key' => count($identifier) == 1 ? $identifier[0] : $identifier[1]];
        // If identifier uses dot notation, add entity_type declaration.
        if (count($identifier) == 2) {
            $where['entity_type'] = $identifier[0];
        }
        return $query->where($where);
    }
}
