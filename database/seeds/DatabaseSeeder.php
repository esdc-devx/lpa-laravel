<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            RoleTableSeeder::class,
            UserTableSeeder::class,
            OrganizationalUnitTableSeeder::class,
            StatesTableSeeder::class,
            RequestOriginTableSeeder::class,
            SchoolPriorityTableSeeder::class,
            CommunitiesTableSeeder::class,
            DepartmentalResultsFrameworkIndicatorTableSeeder::class,
            InternalResourceTableSeeder::class,
            RecurrenceTableSeeder::class,
            RiskTypeTableSeeder::class,
            ProcessFormDecisionTableSeeder::class,
            LearningProductTypeTableSeeder::class,
            InformationSheetDefinitionSeeder::class,
            ProcessProjectApprovalSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
