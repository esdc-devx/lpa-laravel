<?php

use App\Models\Project\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::truncate();

        factory(Project::class, 50)->create()
            ->take(5)->each(function($project) {
                // Complete Project Approval process for the first 5 projects.
                ProcessFactory::startProcess('project-approval', $project)->complete();
            });
    }
}
