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
            OutcomeTypeTableSeeder::class,
            PossibleOfferingTypeTableSeeder::class,
            RegistrationModeTableSeeder::class,
            ContentSourceTypeTableSeeder::class,
            TopicTableSeeder::class,
            ProgramTableSeeder::class,
            SeriesTableSeeder::class,
            CurriculumTypeTableSeeder::class,
            ManagementAccountabilityFrameworkAreaTableSeeder::class,
            CompetencyTableSeeder::class,
            TargetAudienceKnowledgeLevelTableSeeder::class,
            DepartmentTableSeeder::class,
            LearningProductCodeTableSeeder::class,
            DocumentCategoryTableSeeder::class,
            DocumentDenominatorTableSeeder::class,
            MaterialDenominatorTableSeeder::class,
            MaterialItemTableSeeder::class,
            MaterialLocationTableSeeder::class,
            RoomLayoutTableSeeder::class,
            RoomTypeTableSeeder::class,
            RoomUsageTableSeeder::class,
            ContentProviderTableSeeder::class,
            RegionTableSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
