<?php
if (! function_exists('entity_class')) {
    /**
     * Return entity class path from entity type string.
     *
     * @param  string $entityTypeKey
     * @return string
     */
    function entity_class($entityTypeKey)
    {
        if ($entityTypeClass = config('app.entity_types')[$entityTypeKey] ?? null) {
            return $entityTypeClass;
        }
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }
}

if (! function_exists('entity')) {
    /**
     * Resolve an entity from its entity type string.
     * If an id is provided, will also load the specified entity.
     *
     * @param  string $entityTypeKey
     * @param  int $entityId
     * @return \Illuminate\Database\Eloquent\Model
     */
    function entity($entityTypeKey, $entityId = null)
    {
        $entityModel = resolve(entity_class($entityTypeKey));
        return is_null($entityId) ? $entityModel : $entityModel->findOrFail($entityId);
    }
}

if (! function_exists('db_index_name')) {
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
                $length = strlen($item) < 5 ? strlen($item) : 5;
                return substr($item, 0, $length);
            })->toArray(), '_'), 0, 63);
    }
}

if (! function_exists('str_placeholders')) {
    /**
     * Helper function that replaces placeholder attributes within a string.
     *
     * @param  array $replace
     * @param  string $string
     * @return string
     */
    function str_placeholder($replace, $string)
    {
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $string
        );
    }
}

if (! function_exists('str_forward_slash')) {
    /**
     * Helper function that replaces backslashes with forward slashes.
     *
     * @param  string $string
     * @return string
     */
    function str_forward_slash($string)
    {
        return str_replace('\\', '/', $string);
    }
}

if (! function_exists('str_backslash')) {
    /**
     * Helper function that replaces forward slashes with backslashes.
     *
     * @param  string $string
     * @return string
     */
    function str_backslash($string)
    {
        return str_replace('/', '\\', $string);
    }
}
