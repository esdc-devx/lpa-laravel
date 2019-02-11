<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessNotification extends LocalizableModel
{
    use SoftDeletes, UsesKeyNameField, UsesUserAudit;

    protected $fillable = [
        'process_definition_id',
        'name_key',
        'name_en',
        'name_fr',
        'subject_en',
        'subject_fr',
        'body_en',
        'body_fr',
        'created_by',
        'updated_by',
    ];

    protected $localizable = [
        'name',
        'subject',
        'body',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function processDefinition()
    {
        return $this->belongsTo(ProcessDefinition::class);
    }

    /**
     * Resolve a process notification from a process definition key
     * and a notification key.
     *
     * @param  string $processDefinitionKey
     * @param  string $notificationKey
     * @return void
     */
    public static function resolve($processDefinitionKey, $notificationKey) {
        return (new static)->with('processDefinition')
            ->whereHas('processDefinition', function($query) use ($processDefinitionKey) {
                $query->whereNameKey($processDefinitionKey);
            })
            ->whereNameKey($notificationKey)
            ->firstOrfail();
    }
}
