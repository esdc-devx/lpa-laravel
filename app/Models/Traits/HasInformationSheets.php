<?php

namespace App\Models\Traits;

use App\Models\InformationSheet\InformationSheet;

/**
 * Add required relationship for information sheets.
 */
trait HasInformationSheets
{
    public static function bootHasInformationSheets()
    {
        // Delete any information sheets associated to deleted entity.
        static::deleting(function ($model) {
            $model->informationSheets->each->delete();
        });
    }

    public function informationSheets()
    {
        return $this->morphMany(InformationSheet::class, 'entity');
    }
}
