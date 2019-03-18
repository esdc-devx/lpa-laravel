<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;
use App\Models\Lists\RoomLayout;
use App\Models\Lists\RoomType;
use App\Models\Lists\RoomUsage;

class Room extends BaseModel
{
    protected $fillable = [
        'operational_details_id',
        'quantity',
        'room_layout_id',
        'room_layout_other_en',
        'room_layout_other_fr',
        'room_type_id',
        'room_usage_id',
        'special_requirement_description_en',
        'special_requirement_description_fr',
    ];

    protected $with = [
        'roomUsage',
        'roomType',
        'roomLayout',
    ];

    public $timestamps = false;

    public function roomUsage()
    {
        return $this->belongsTo(RoomUsage::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function roomLayout()
    {
        return $this->belongsTo(RoomLayout::class);
    }
}
