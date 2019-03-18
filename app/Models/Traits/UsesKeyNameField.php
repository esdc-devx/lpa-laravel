<?php

namespace App\Models\Traits;

trait UsesKeyNameField
{
    /**
     * Add a query scope accessor by name_key value.
     *
     * @param  Illuminate\Database\Query $query
     * @param  string $key | Identifier used to retrieve an entry by name_key.
     *                       Key can be composed with entity_type using dot not notation (i.e. project.completed)
     * @return void
     */
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

    /**
     * Return the id from name_key field value.
     *
     * @param  string $key
     * @return int
     */
    public static function getIdFromKey($key)
    {
        return static::getByKey($key)->firstOrFail()->id;
    }
}
