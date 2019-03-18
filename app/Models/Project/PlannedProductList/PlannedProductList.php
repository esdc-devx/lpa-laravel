<?php

namespace App\Models\Project\PlannedProductList;

use App\Models\Process\ProcessInstanceFormDataModel;

class PlannedProductList extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'process_instance_form_id',
        'process_instance_id',
    ];

    protected $with = [
        'plannedProducts',
    ];

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
