<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;
use App\Models\Lists\DocumentCategory;
use App\Models\Lists\DocumentDenominator;

class Document extends BaseModel
{
    protected $fillable = [
        'document_category_id',
        'document_category_other_en',
        'document_category_other_fr',
        'document_denominator_id',
        'link_en',
        'link_fr',
        'notes_en',
        'notes_fr',
        'operational_details_id',
        'printing_specifications_en',
        'printing_specifications_fr',
        'quantity',
        'reusable',
        'title_en',
        'title_fr',
        'version',
    ];

    protected $with = [
        'documentCategory',
        'documentDenominator',
    ];

    protected $casts = [
        'reusable' => 'boolean',
    ];

    public $timestamps = false;

    public function documentCategory()
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    public function documentDenominator()
    {
        return $this->belongsTo(DocumentDenominator::class);
    }
}
