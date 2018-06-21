<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;

class ProcessForm extends LocalizableModel
{
    protected $hidden = ['id', 'process_step_id'];
    protected $localizable = ['name'];

    public $timestamps = false;
}
