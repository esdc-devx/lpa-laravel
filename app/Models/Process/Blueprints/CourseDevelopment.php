<?php

namespace App\Models\Process\Blueprints;

use App\Process\ProcessDefinitionBlueprint;

class CourseDevelopment extends ProcessDefinitionBlueprint
{
    public function getDefinition()
    {
        return [
            'entity_types' => ['course'],
            'name_en'      => 'Course Development',
            'name_fr'      => 'Développement d\'un cours',
        ];
    }

    public function getStructure()
    {
        return collect([
            [
                'name_en' => 'Design',
                'name_fr' => 'Conception',
                'forms'   => [
                    [
                        'name_en' => 'Design',
                        'name_fr' => 'Conception',
                    ],
                    [
                        'name_en'     => 'Design Assessment',
                        'name_fr'     => 'Évaluation de la conception',
                        'assessments' => ['design'],
                    ],
                ],
            ],
            [
                'name_en' => 'Development',
                'name_fr' => 'Développement',
                'forms'   => [
                    [
                        'name_en' => 'Operational Details',
                        'name_fr' => 'Détails opérationnels',
                    ],
                    [
                        'name_en' => 'Solution Development',
                        'name_fr' => 'Développement de la solution',
                    ],
                    [
                        'name_en' => 'Offering and Enrolment Estimates',
                        'name_fr' => 'Estimations de l\'offre et des inscriptions',
                    ],
                    [
                        'name_en'     => 'Development Assessment',
                        'name_fr'     => 'Évaluation du développement',
                        'assessments' => ['operational-details', 'solution-development', 'offering-and-enrolment-estimates'],
                    ],
                ],
            ],
            [
                'name_en' => 'Communications Review',
                'name_fr' => 'Revue des communications',
                'forms'   => [
                    [
                        'name_en' => 'Communications Material',
                        'name_fr' => 'Matériel de communication',
                    ],
                    [
                        'name_en'     => 'Communications Material Assessment',
                        'name_fr'     => 'Évaluation du matériel de communication',
                        'assessments' => ['communications-material'],
                    ],
                ],
            ],
        ]);
    }

    public function getNotifications()
    {
        return collect([
            [
                'name_en' => 'Design Ready for Assessment',
                'name_fr' => 'Conception prête pour l\'évaluation',
            ],
            [
                'name_en' => 'Cancelled',
                'name_fr' => 'Annulé',
            ],
            [
                'name_key' => 'design-rejected',
                'name_en'  => 'Design Requires Adjustments',
                'name_fr'  => 'Conception nécessite des ajustements',
            ],
            [
                'name_en' => 'Operational Details Required',
                'name_fr' => 'Détails opérationnels requis',
            ],
            [
                'name_en' => 'Solution Required',
                'name_fr' => 'Développement de la solution requis',
            ],
            [
                'name_en' => 'Offering and Enrolment Estimates Required',
                'name_fr' => 'Estimations de l\'offre et des inscriptions requis',
            ],
            [
                'name_en' => 'Development Ready for Assessment',
                'name_fr' => 'Développement prêt pour l\'évaluation',
            ],
            [
                'name_key' => 'course-operational-details-rejected',
                'name_en'  => 'Operational Details Requires Adjustments',
                'name_fr'  => 'Détails opérationnels nécessitent des ajustements',
            ],
            [
                'name_key' => 'course-solution-effort-rejected',
                'name_en'  => 'Solution Requires Adjustments',
                'name_fr'  => 'Développement de la solution nécessitent des ajustements',
            ],
            [
                'name_key' => 'course-offering-and-enrolment-estimates-rejected',
                'name_en'  => 'Offering and Enrolment Estimates Requires Adjustments',
                'name_fr'  => 'Estimations de l\'offre et des inscriptions nécessitent des ajustements',
            ],
            [
                'name_en' => 'Communication Material Required',
                'name_fr' => 'Matériel de communication requis',
            ],
            [
                'name_en' => 'Communication Material Ready for Assessment',
                'name_fr' => 'Le matériel de communication est prêt pour l\'évaluation',
            ],
            [
                'name_key' => 'course-communication-material-rejected',
                'name_en'  => 'Communication Material Requires Adjustments',
                'name_fr'  => 'Le matériel de communication nécessite des ajustements',
            ],
            [
                'name_en' => 'Ready for Publishing',
                'name_fr' => 'Prêt pour la publication',
            ],
        ]);
    }

    public function getInformationSheets()
    {
        return collect([]);
    }

    public function authorize($learningProduct)
    {
        // Only start this process if learning product is still new.
        return $learningProduct->state->name_key == 'new';
    }

    public function getProcessVariablesFor($learningProduct)
    {
        return [
            // Send course type so that the process can ajdust itself.
            'courseType' => [ 'type' => 'String', 'value' => $learningProduct->subType->name_key],
        ];
    }
}
