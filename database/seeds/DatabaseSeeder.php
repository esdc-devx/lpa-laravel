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
            ProcessProjectApprovalSeeder::class,
            RequestSourceTableSeeder::class,
            PotentialSolutionTypeTableSeeder::class,
            GovernmentPriorityTableSeeder::class,
            CommunitiesTableSeeder::class,
            TimeframeTableSeeder::class,
            DepartmentalBenefitTypeTableSeeder::class,
            LearnersBenefitTypeTableSeeder::class,
            MaintenanceFundTableSeeder::class,
            SalaryFundTableSeeder::class,
            InternalResourceTableSeeder::class,
            RiskTypeTableSeeder::class,
            RiskImpactLevelTableSeeder::class,
            RiskProbabilityLevelTableSeeder::class,
            ProcessFormDecisionTableSeeder::class,
            LearningProductTypeTableSeeder::class,
            ProjectTableSeeder::class,
            LearningProductTableSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
