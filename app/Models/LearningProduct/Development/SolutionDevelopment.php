<?php

namespace App\Models\LearningProduct\Development;

use App\Models\Process\ProcessInstanceFormDataModel;
use App\Models\Lists\ContentProvider;

class SolutionDevelopment extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'accessibility_qa_completed',
        'client_acceptance_test_completed',
        'comments',
        'design_provider_id',
        'design_provider_other',
        'effort_required',
        'functional_qa_completed',
        'implementation_provider_id',
        'implementation_provider_other',
        'instructional_designer_qa_completed',
        'language_content_qa_completed',
        'process_instance_form_id',
        'process_instance_id',
    ];

    protected $with = [
        'designProvider',
        'implementationProvider',
    ];

    protected $casts = [
        'accessibility_qa_completed' => 'boolean',
        'client_acceptance_test_completed' => 'boolean',
        'functional_qa_completed' => 'boolean',
        'instructional_designer_qa_completed' => 'boolean',
        'language_content_qa_completed' => 'boolean',
    ];

    public function designProvider()
    {
        return $this->belongsTo(ContentProvider::class, 'design_provider_id');
    }

    public function implementationProvider()
    {
        return $this->belongsTo(ContentProvider::class, 'implementation_provider_id');
    }
}
