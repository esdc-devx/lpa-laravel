<?php

namespace App\Models\InformationSheet;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformationSheet extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'information_sheet_definition_id',
        'entity_type',
        'entity_id',
    ];

    protected $with = [
        'definition',
    ];

    protected $sheetClass;

    public $timestamps = false;

    public function entity()
    {
        return $this->morphTo();
    }

    public function definition()
    {
        return $this->belongsTo(InformationSheetDefinition::class, 'information_sheet_definition_id');
    }

    /**
     * Create an information sheet for an entity based on definition name key.
     *
     * @param  BaseModel $entity
     * @param  string $informationSheetKey
     * @return InformationSheet
     */
    public static function createFromDefinition($entity, $informationSheetKey)
    {
        // Retrieve information sheet definition using entity type and name key.
        $informationSheetDefinition = InformationSheetDefinition::where([
            'entity_type' => $entity::getEntityType(),
            'name_key'    => $informationSheetKey,
        ])->firstOrFail();

        return static::create([
            'information_sheet_definition_id' => $informationSheetDefinition->id,
            'entity_type'                     => $entity::getEntityType(),
            'entity_id'                       => $entity->id,
        ]);
    }

    /**
     * Defer to information sheet class to get data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->sheet->getData($this->entity);
    }

    /**
     * Resolve information sheet view file path attribute.
     * These files should reside in /resources/views/information_sheets/sheets.
     *
     * @return string
     */
    public function getViewFilePathAttribute()
    {
        $entityType = snake_case(class_basename($this->entity));
        $sheetPath = str_replace('-', '_', $this->definition->name_key);
        return "$entityType.$sheetPath";
    }

    /**
     * Dynamically resolve information sheet class from entity and definition name key.
     *
     * @return mixed
     */
    protected function getSheetAttribute()
    {
        if ($this->sheetClass) {
            return $this->sheetClass;
        }

        $entityType = studly_case(class_basename($this->entity));

        $classPath = collect(explode('.', $this->definition->name_key))
            ->transform(function ($item) {
                return studly_case($item);
            })
            ->implode('\\');

        $informationSheetClass = "App\Models\InformationSheet\Sheets\\{$entityType}\\{$classPath}";
        if (! class_exists($informationSheetClass)) {
            throw new \Exception("Information sheet class [$informationSheetClass] not found.");
        }
        $this->sheetClass = new $informationSheetClass;
        return $this->sheetClass;
    }
}
