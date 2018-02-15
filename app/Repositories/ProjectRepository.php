<?php

namespace App\Repositories;

use App\Models\Project\Project;

class ProjectRepository
{
    public function getAll($limit = 0)
    {
        // @todo: Add customizable sorting, relationships?
        $results = Project::with(['owner', 'organizationUnit'])
            ->orderBy('name', 'asc');

        return $limit ? $results->paginate($limit) : $results->get();
    }
}
