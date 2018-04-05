<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Project\Project;
use App\Models\OrganizationalUnit\OrganizationalUnit;

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
    }
}
