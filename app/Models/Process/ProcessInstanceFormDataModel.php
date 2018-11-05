<?php

namespace App\Models\Process;

use App\Models\BaseModel;

class ProcessInstanceFormDataModel extends BaseModel
{
    protected $hidden = ['process_instance_form_id'];

    public $timestamps = false;
    public $relationships = [];

    public function processInstanceForm()
    {
        return $this->belongsTo(ProcessInstanceForm::class)
            ->with('state', 'organizationalUnit', 'currentEditor', 'updatedBy');
    }

    public function saveFormData(array $data)
    {
        // Update properties on model.
        $this->update($data);

        // Return model with all of its updated relationships
        // and format list output to only return ids.
        return $this->load($this->relationships)->formatListsOutput();
    }
}