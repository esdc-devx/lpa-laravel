<?php

namespace App\Models\LearningProduct;

class Course extends LearningProduct
{
    // Stored in the $singleTableTypeField database column defined in parent class.
    protected static $singleTableType = 'course';
}
