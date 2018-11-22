<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class ProcessNotification extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'process_definition_id',
        'name_key',
        'name_en',
        'name_fr',
        'subject_en',
        'subject_fr',
        'body_en',
        'body_fr',
    ];

    protected $localizable = [
        'name',
        'subject',
        'body',
    ];

    public $timestamps = false;

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
