<?php

use App\Models\LearningProduct\Design\Design;
use App\Models\LearningProduct\LearningProductCode;
use App\Models\Lists\Community;
use App\Models\Lists\Competency;
use App\Models\Lists\ContentSourceType;
use App\Models\Lists\CurriculumType;
use App\Models\Lists\Department;
use App\Models\Lists\ManagementAccountabilityFrameworkArea;
use App\Models\Lists\OutcomeType;
use App\Models\Lists\PossibleOfferingType;
use App\Models\Lists\Program;
use App\Models\Lists\RegistrationMode;
use App\Models\Lists\Series;
use App\Models\Lists\TargetAudienceKnowledgeLevel;
use App\Models\Lists\Topic;
use Faker\Generator as Faker;

$factory->define(Design::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'additional_costs'                          => [
            [
                'rationale' => $faker->paragraph(),
                'costs'     => $faker->roundNumberBetween(1000, 999999),
            ],
        ],
        'applicable_policies'                       => [
            [
                'name' => $faker->sentenceNoDot()
            ],
        ],
        'comments'                                  => $faker->paragraph(),
        'communities'                               => $faker->randomMultipleList(Community::all(), 1, 1),
        'competencies'                              => $faker->randomMultipleList(Competency::all(), 0, 3),
        'complementary_learning_products'           => $faker->randomMultipleList(LearningProductCode::all(), 0, 3),
        'content_source_codes'                      => function (array $data) use ($faker)
        {
            return $data['content_source_type_id'] == 1 ? $faker->randomMultipleList(LearningProductCode::all(), 1, 3) : [];
        },
        'content_source_type_id'                    => $faker->randomList(ContentSourceType::all()),
        'curriculum_type_id'                        => $faker->randomList(CurriculumType::all()),
        'description'                               => $faker->paragraph(),
        'expected_annual_participant_number'        => $faker->roundNumberBetween(1000, 100000),
        'expected_launch_date'                      => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'expected_pilot_start_date'                 => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'internal_order'                            => $faker->internalOrder(),
        'is_required_training'                      => $faker->boolean(),
        'learning_objectives'                       => $faker->paragraph(),
        'learning_product_code_id'                  => $faker->randomList(LearningProductCode::all()),
        'management_accountability_framework_areas' => $faker->randomMultipleList(ManagementAccountabilityFrameworkArea::all(), 1, 5),
        'mandatory_prerequisites'                   => $faker->randomMultipleList(LearningProductCode::all(), 0, 5),
        'outcome_types'                             => $faker->randomMultipleList(OutcomeType::all()),
        'possible_offering_types'                   => $faker->randomMultipleList(PossibleOfferingType::all()),
        'prescribed_by_departments'                 => function (array $data) use ($faker)
        {
            return $data['prescribed_by_tbs'] == true ? [] : $faker->randomMultipleList(Department::all(), 0, 3);
        },
        'prescribed_by_tbs'                         => $faker->boolean(),
        'programs'                                  => $faker->randomMultipleList(Program::all(), 0, 1),
        'recommended_by_departments'                => function (array $data) use ($faker)
        {
            return $data['prescribed_by_tbs'] == true ? [] : $faker->randomMultipleList(Department::all(), 0, 3);
        },
        'recommended_prerequisites'                 => $faker->randomMultipleList(Department::all(), 0, 3),
        'recommended_review_interval'               => 36,
        'registration_mode_id'                      => $faker->randomList(RegistrationMode::all()),
        'sequence'                                  => 1,
        'series'                                    => $faker->randomMultipleList(Series::all(), 0, 1),
        'target_audience_description'               => $faker->paragraph(),
        'target_audience_knowledge_level_id'        => $faker->randomList(TargetAudienceKnowledgeLevel::all()),
        'topics'                                    => $faker->randomMultipleList(Topic::all(), 1, 3),
        'total_duration'                            => $faker->roundNumberBetween(300, 100000),
        'version'                                   => 1,
    ];
});
