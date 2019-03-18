<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;
use App\Models\Lists\MaterialDenominator;
use App\Models\Lists\MaterialItem;
use App\Models\Lists\MaterialLocation;

class Material extends BaseModel
{
    protected $fillable = [
        'material_denominator_id',
        'material_item_id',
        'material_item_other_en',
        'material_item_other_fr',
        'material_location_id',
        'notes_en',
        'notes_fr',
        'operational_details_id',
        'quantity',
        'reusable',
    ];

    protected $with = [
        'materialDenominator',
        'materialItem',
        'materialLocation',
    ];

    protected $casts = [
        'reusable' => 'boolean',
    ];

    public $timestamps = false;

    public function materialItem()
    {
        return $this->belongsTo(MaterialItem::class);
    }

    public function materialDenominator()
    {
        return $this->belongsTo(MaterialDenominator::class);
    }

    public function materialLocation()
    {
        return $this->belongsTo(MaterialLocation::class);
    }
}
