<?php

namespace App\Models\Project\ArchitecturePlan;

use App\Models\BaseModel;
use App\Models\LearningProduct\LearningProductType;

class PlannedProduct extends BaseModel
{
    protected $hidden = ['pivot'];
    protected $fillable = [
        'type_id', 'sub_type_id', 'quantity',
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
