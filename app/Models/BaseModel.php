<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $formatListsOutput = false;
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
     * Synchronize many to many relationships.
     *
     * @param  array $data
     * @param  array $relationships
     * @return void
     */
    protected function syncRelationships($data, $relationships)
    {
        foreach ($relationships as $relationship) {
            $key = snake_case($relationship);
            if (isset($data[$key])) {
                // Synchronize relationship.
                $this->{$relationship}()->sync($data[$key]);
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
    protected function syncRelatedModels($data, $relationships)
    {
        foreach ($relationships as $relationship) {
            // Check if there is some data for the relationship.
            $key = snake_case($relationship);
            if (isset($data[$key])) {
                $ids = [];
                $attribute = $data[$key];
                $relationClass = class_basename($this->{$relationship}());
                $relatedModelClass = get_class($this->{$relationship}()->getRelated());
                // Create or update all related models and store their ids to
                // synchronize them (if necessary).
                foreach ($attribute as $item) {
                    // Add foreign key field used for one to many relationships.
                    if ($relationClass === 'HasMany') {
                        $item[$this->{$relationship}()->getForeignKeyName()] = $this->id;
                    }
                    $ids[] = $relatedModelClass::updateOrCreate(['id' => $item['id'] ?? null], $item)->id;
                }

                // If relation is many to many, we also need to synchronize relationships.
                if ($relationClass === 'BelongsToMany') {
                    $this->{$relationship}()->sync($ids);
                }

                // Delete model entries that are no longer referenced.
                $previous = $this->{$relationship}->pluck('id');
                $remove = $previous->diff($ids)->values()->all();
                $relatedModelClass::destroy($remove);
            }
        }
    }

    /**
     * Once called, model will render its lists as an array of ids.
     * These lists must extend the ListableModel class and
     * be defined inside a $relationships property on the model.
     *
     * @return $this
     */
    public function formatListsOutput()
    {
        // Set flag which will be handled in the toArray() method.
        $this->formatListsOutput = true;

        if (isset($this->relationships)) {
            foreach ($this->relationships as $relationship) {
                $relatedModelClass = $this->{$relationship}()->getRelated();

                // If relationship is an instance of the BaseModel class and was also loaded,
                // call formatListsOutput() method recursively.
                if (get_parent_class($relatedModelClass) === 'App\Models\BaseModel' && $this->relationLoaded($relationship)) {
                    $this->{$relationship}->each(function ($instance) {
                        $instance->formatListsOutput();
                    });
                }
            }
        }

        return $this;
    }

    /**
     * Override method used when returning the model as an array.
     *
     * @return array
     */
    public function toArray()
    {
        // Get the model formatted as an array.
        $output = parent::toArray();

        // Check if lists should be re-formatted to return only ids.
        if ($this->formatListsOutput && isset($this->relationships)) {
            foreach ($this->relationships as $relationship) {
                $relatedModelClass = $this->{$relationship}()->getRelated();

                // If relationship extends ListableModel, only return an array of ids.
                if (get_parent_class($relatedModelClass) === 'App\Models\ListableModel') {
                    if ($attribute = &$output[snake_case($relationship)]) {
                        $attribute = collect($attribute)->pluck('id');
                    }
                }
            }
        }

        return $output;
    }
}
