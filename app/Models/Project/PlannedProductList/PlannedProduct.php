<?php

namespace App\Models\Project\PlannedProductList;

use App\Models\BaseModel;
use App\Models\LearningProduct\LearningProductType;

class PlannedProduct extends BaseModel
{
    protected $fillable = [
        'planned_product_list_id',
        'type_id',
        'sub_type_id',
        'quantity',
    ];

    protected $with = [
        'type',
        'subType',
    ];

    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(LearningProductType::class);
    }

    public function subType()
    {
        return $this->belongsTo(LearningProductType::class);
    }
}
