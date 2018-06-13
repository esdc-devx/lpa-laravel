<?php
if (!function_exists('entity')) {
    /**
     * Resolve an entity from its entity type string.
     *
     * @param  string $entityTypeKey
     * @return \Illuminate\Database\Eloquent\Model
     */
    function entity($entityTypeKey)
    {
        if ($entityTypeClass = config('app.entity_types')[$entityTypeKey] ?? null) {
            return resolve($entityTypeClass);
        }
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }
}

if (!function_exists('db_index_name')) {
    /**
     * Generate a compact database index name which respects
     * MySQL constraint of maximum 64 characters.
     *
     * @param  string $name
     * @return string
     */
    function db_index_name($name)
    {
        return substr(implode(collect(explode('_', $name))
            ->transform(function ($item, $key) {
                return substr($item, 0, 3);
            })->toArray(), '_'), 0, 63);
    }
}
