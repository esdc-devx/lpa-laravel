<?php

namespace App\Models\LearningProduct;

use App\Models\ListableModel;

class LearningProductType extends ListableModel
{
    public function scopeSubTypes($query)
    {
        $query->where('parent_id', '!=', 0);
    }
}
