<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = [];

    /**
     * Return entity type string from model class name.
     *
     * @return string
     */
    public static function getEntityType()
    {
        return kebab_case(class_basename(static::class));
    }

    /**
     * Synchronize relationships.
     *
     * @param  array $data
     * @param  array $relationships
     * @return void
     */
    protected function syncRelationships(&$data, $relationships)
    {
        foreach ($relationships as $relationship) {
            $key = snake_case($relationship);
            if (isset($data[$key])) {
                // Synchronize relationship.
                $this->{$relationship}()->sync($data[$key]);

                // Remove updated relation from data set.
                unset($data[$key]);
            }
        }
    }

    /**
     * Create, update or remove related models (complex data).
     *
     * @param  array $data
     * @param  array $relationships
     * @return void
     */
    protected function syncRelatedModels(&$data, $relationships)
    {
        foreach ($relationships as $relationship) {
            $key = snake_case($relationship);
            if (isset($data[$key])) {
                $ids = [];
                $relatedModelClassName = get_class($this->{$relationship}()->getRelated());

                // Create or update related models.
                foreach ($data[$key] as $item) {
                    $ids[] = $relatedModelClassName::updateOrCreate(['id' => $item['id'] ?? null], $item)->id;
                }

                // Synchronize relationships.
                $this->{$relationship}()->sync($ids);

                // Delete model entries that are no longer referenced.
                $previous = $this->{$relationship}->pluck('id');
                $remove = $previous->diff($ids)->values()->all();
                $relatedModelClassName::destroy($remove);

                // Remove updated relation from data set.
                unset($data[$key]);
            }
        }
    }
}
