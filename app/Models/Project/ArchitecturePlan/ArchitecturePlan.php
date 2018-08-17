<?php

namespace App\Models\Project\ArchitecturePlan;

use App\Models\Process\ProcessInstanceFormDataModel;

class ArchitecturePlan extends ProcessInstanceFormDataModel
{
    protected $fillable = ['process_instance_form_id', 'comment'];

    public $relationships = ['plannedProducts'];

    public function plannedProducts()
    {
        return $this->hasMany(PlannedProduct::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'plannedProducts',
        ]);

        parent::saveFormData($data);
    }
}
