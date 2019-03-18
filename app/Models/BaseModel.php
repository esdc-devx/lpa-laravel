<?php

namespace App\Models;

use App\Models\ListableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaseModel extends Model
{
    protected $formatListsOutput = false;
    protected $fillable = [];

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
     * Check if attribute is supported on model.
     *
     * @param  string $attr
     * @return boolean
     */
    public function hasAttribute($attr)
    {
        return collect($this->getFillable())->contains($attr);
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
                $relationshipClass = get_class($this->{$relationship}());
                $relatedModelClass = get_class($this->{$relationship}()->getRelated());
                // Create or update all related models and store their ids to
                // synchronize them (if necessary).
                foreach ($attribute as $item) {
                    // Add foreign key field used for one to many relationships.
                    if ($relationshipClass === HasMany::class) {
                        $item[$this->{$relationship}()->getForeignKeyName()] = $this->id;
                    }
                    $ids[] = $relatedModelClass::updateOrCreate(['id' => $item['id'] ?? null], $item)->id;
                }

                // If relation is many to many, we also need to synchronize relationships.
                if ($relationshipClass === BelongsToMany::class) {
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
     * Once called, model will render its multiple choice lists as an array of ids.
     * These lists must extend the ListableModel class and be defined as a many to many relationship.
     * The relationships must also be defined in the $with model property.
     * @note: This whole mechanism could potentially be replaced with json resource classes instead.
     *
     * @return $this
     */
    public function formatListsOutput()
    {
        // Set flag which will be handled in the toArray() method.
        $this->formatListsOutput = true;

        if (! empty($this->with)) {
            foreach ($this->with as $relationship) {
                $relationshipClass = get_class($this->{$relationship}());
                $relatedModelClass = $this->{$relationship}()->getRelated();
                $isRelatedModel = get_parent_class($relatedModelClass) === BaseModel::class && $relationshipClass === HasMany::class;
                $isRelationshipLoaded = $this->relationLoaded($relationship);
                // If relationship is a related model and its being loaded, call its formatListsOutput() method recursively.
                if ($isRelatedModel && $isRelationshipLoaded) {
                    $this->{$relationship}->each(function ($relatedModel) {
                        $relatedModel->formatListsOutput();
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
        if ($this->formatListsOutput && ! empty($this->with)) {
            foreach ($this->with as $relationship) {
                $relationshipClass = get_class($this->{$relationship}());
                $relatedModelClass = $this->{$relationship}()->getRelated();

                // If relationship extends ListableModel and is multiple choice, return an array of ids.
                if (get_parent_class($relatedModelClass) === ListableModel::class && $relationshipClass === BelongsToMany::class) {
                    if ($attribute = &$output[snake_case($relationship)]) {
                        $attribute = collect($attribute)->pluck('id');
                    }
                }
            }
        }

        return $output;
    }
}
