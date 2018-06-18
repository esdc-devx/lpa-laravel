<?php

namespace App\Http\Controllers;

use App\Models\ListableModel;

class ListController extends APIController
{
    /**
     * Render a list of values as an array with parent/child relationship.
     *
     * @param  App\Models\BaseModel $listEntity
     * @return array
     */
    protected function render($listEntity)
    {
        if (is_subclass_of($listEntity, ListableModel::class)) {
            return $listEntity::toListArray();
        }
        return [];
    }

    /**
     * Retrieve a list from its entity type string.
     *
     * @param  App\Models\BaseModel $listEntity
     * @return \Illuminate\Http\Response
     */
    public function show($listEntity)
    {
        return $this->respond(
            $this->render($listEntity)
        );
    }

    /**
     * Retrieve multiple lists from an "include" array parameter.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMultiple()
    {
        $lists = [];
        if (!$include = request()->get('include')) {
            return $this->respondInvalidRequest('Must provide an "include" parameter.');
        }

        foreach ($include as $entityTypeKey) {
            if ($listEntity = entity($entityTypeKey)) {
                $lists[$entityTypeKey] = $this->render($listEntity);
            }
        }

        return $this->respond($lists);
    }
}
