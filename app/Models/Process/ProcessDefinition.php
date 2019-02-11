<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class ProcessDefinition extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'name_key',
        'name_en',
        'name_fr',
    ];

    protected $localizable = [
        'name',
    ];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'name_key';
    }

    public function entityTypes()
    {
        return $this->hasMany(ProcessDefinitionEntityType::class);
    }

    public function steps()
    {
        return $this->hasMany(ProcessStep::class);
    }

    public function forms()
    {
        return $this->hasManyThrough(ProcessForm::class, ProcessStep::class);
    }

    public function notifications()
    {
        return $this->hasMany(ProcessNotification::class);
    }

    /**
     * Get available process definitions for a given entity.
     *
     * @param  App\Models\BaseModel $entityClass
     * @return Illuminate\Support\Collection
     */
    public static function getFor($entityClass)
    {
        return static::whereHas('entityTypes', function ($query) use ($entityClass) {
            $query->whereEntityType($entityClass::getEntityType());
        })->get();
    }

    /**
     * Defer to static method to resolve blueprint class.
     *
     * @return void
     */
    public function getBlueprintAttribute()
    {
        return static::getBlueprintFor($this->name_key);
    }

    /**
     * Resolve blueprint class from definition name_key.
     *
     * @param  string $key
     * @return ProcessDefinitionBlueprint
     */
    public static function getBlueprintFor($key)
    {
        $classname = studly_case($key);
        $blueprintClass = "App\Models\Process\Blueprints\\{$classname}";
        if (! class_exists($blueprintClass)) {
            throw new \Exception("Process definition blueprint class [$blueprintClass] could not be found.");
        }
        return new $blueprintClass;
    }

    /**
     * Defer to blueprint class to send any extra variables to Camunda
     * upon process initiation.
     *
     * @param  App\Models\BaseModel $entity
     * @return array
     */
    public function getProcessVariablesFor($entity)
    {
        return $this->blueprint->getProcessVariablesFor($entity);
    }

    /**
     * Authorize process initiation for a given entity.
     *
     * @param  App\Models\BaseModel $entity
     * @return boolean
     */
    public function authorize($entity)
    {
        // Ensure process definition supports entity type.
        if (! $this->entityTypes->contains('entity_type', $entity::getEntityType())) {
            return false;
        }
        // Add extra authorization rules defined by process definition blueprint class.
        return $this->blueprint->authorize($entity);
    }
}
