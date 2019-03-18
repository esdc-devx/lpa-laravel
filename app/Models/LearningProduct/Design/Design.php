<?php

namespace App\Models\LearningProduct\Design;

use App\Models\LearningProduct\Design\AdditionalCost;
use App\Models\LearningProduct\Design\ApplicablePolicy;
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
use App\Models\Process\ProcessInstanceFormDataModel;

class Design extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'content_source_type_id',
        'curriculum_type_id',
        'description',
        'expected_annual_participant_number',
        'expected_launch_date',
        'expected_pilot_start_date',
        'internal_order',
        'is_required_training',
        'learning_objectives',
        'learning_product_code_id',
        'prescribed_by_tbs',
        'process_instance_form_id',
        'process_instance_id',
        'recommended_review_interval',
        'registration_mode_id',
        'sequence',
        'target_audience_description',
        'target_audience_knowledge_level_id',
        'total_duration',
        'version',
    ];

    protected $with = [
        'additionalCosts',
        'applicablePolicies',
        'communities',
        'competencies',
        'complementaryLearningProducts',
        'contentSourceCodes',
        'contentSourceType',
        'curriculumType',
        'learningProductCode',
        'managementAccountabilityFrameworkAreas',
        'mandatoryPrerequisites',
        'outcomeTypes',
        'possibleOfferingTypes',
        'prescribedByDepartments',
        'programs',
        'recommendedByDepartments',
        'recommendedPrerequisites',
        'registrationMode',
        'series',
        'targetAudienceKnowledgeLevel',
        'topics',
    ];

    protected $casts = [
        'is_required_training' => 'boolean',
        'prescribed_by_tbs' => 'boolean',
    ];

    public function learningProductCode()
    {
        return $this->belongsTo(LearningProductCode::class);
    }

    public function outcomeTypes()
    {
        return $this->belongsToMany(OutcomeType::class);
    }

    public function possibleOfferingTypes()
    {
        return $this->belongsToMany(PossibleOfferingType::class);
    }

    public function registrationMode()
    {
        return $this->belongsTo(RegistrationMode::class);
    }

    public function applicablePolicies()
    {
        return $this->hasMany(ApplicablePolicy::class);
    }

    public function contentSourceType()
    {
        return $this->belongsTo(ContentSourceType::class);
    }

    public function contentSourceCodes()
    {
        return $this->belongsToMany(LearningProductCode::class, 'design_content_source_code', 'design_id', 'learning_product_code_id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }

    public function series()
    {
        return $this->belongsToMany(Series::class);
    }

    public function curriculumType()
    {
        return $this->belongsTo(CurriculumType::class);
    }

    public function managementAccountabilityFrameworkAreas()
    {
        return $this->belongsToMany(ManagementAccountabilityFrameworkArea::class);
    }

    public function competencies()
    {
        return $this->belongsToMany(Competency::class, 'design_competency');
    }

    public function targetAudienceKnowledgeLevel()
    {
        return $this->belongsTo(TargetAudienceKnowledgeLevel::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'design_community');
    }

    public function mandatoryPrerequisites()
    {
        return $this->belongsToMany(LearningProductCode::class, 'design_mandatory_prerequisite', 'design_id', 'learning_product_code_id');
    }

    public function recommendedPrerequisites()
    {
        return $this->belongsToMany(LearningProductCode::class, 'design_recommended_prerequisite', 'design_id', 'learning_product_code_id');
    }

    public function complementaryLearningProducts()
    {
        return $this->belongsToMany(LearningProductCode::class, 'design_complementary_learning_product', 'design_id', 'learning_product_code_id');
    }

    public function prescribedByDepartments()
    {
        return $this->belongsToMany(Department::class, 'design_prescribed_by_department', 'design_id', 'department_id');
    }

    public function recommendedByDepartments()
    {
        return $this->belongsToMany(Department::class, 'design_recommended_by_department', 'design_id', 'department_id');
    }

    public function additionalCosts()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelationships($data, [
            'outcomeTypes',
            'possibleOfferingTypes',
            'contentSourceCodes',
            'topics',
            'programs',
            'series',
            'managementAccountabilityFrameworkAreas',
            'competencies',
            'communities',
            'mandatoryPrerequisites',
            'recommendedPrerequisites',
            'complementaryLearningProducts',
            'prescribedByDepartments',
            'recommendedByDepartments',
        ]);

        $this->syncRelatedModels($data, [
            'applicablePolicies',
            'additionalCosts',
        ]);

        parent::saveFormData($data);
    }
}
