<?php

namespace App\Models\Traits;

use App\Events\ProcessEntityDeleted;
use App\Events\ProcessEntityUpdated;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessInstance;
use App\Models\State;

/**
 * Add required relationships for processes.
 */
trait HasProcesses
{
    public static function bootHasProcesses()
    {
        // Fire an event when model gets updated.
        static::updating(function ($model) {
            event(new ProcessEntityUpdated($model));
        });

        // Fire an event when model gets deleted.
        static::deleting(function ($model) {
            event(new ProcessEntityDeleted($model));
        });
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function processInstances()
    {
        return $this->morphMany(ProcessInstance::class, 'entity');
    }

    public function currentProcess()
    {
        return $this->belongsTo(ProcessInstance::class, 'process_instance_id');
    }

    public function scopeWhereState($query, $stateKey)
    {
        $stateIds = State::where('entity_type', static::getEntityType())
            ->whereIn('name_key', collect($stateKey))
            ->get()->pluck('id');

        $query->whereIn('state_id', $stateIds);
    }
}
