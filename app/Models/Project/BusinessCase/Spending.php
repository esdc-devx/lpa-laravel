<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Lists\InternalResource;
use App\Models\Lists\Recurrence;

class Spending extends BaseModel
{
    protected $fillable = [
        'business_case_id',
        'internal_resource_id',
        'cost_description',
        'cost',
        'recurrence_id',
        'comments',
    ];

    protected $with = [
        'internalResource',
        'recurrence',
    ];

    public $timestamps = false;

    public function internalResource()
    {
        return $this->belongsTo(InternalResource::class);
    }

    public function recurrence()
    {
        return $this->belongsTo(Recurrence::class);
    }
}
