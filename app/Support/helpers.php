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
