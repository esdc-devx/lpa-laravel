<?php

namespace App\Models\Process\Blueprints;

use App\Process\ProcessDefinitionBlueprint;

class ProjectApproval extends ProcessDefinitionBlueprint
{
    public function getDefinition()
    {
        return [
            'entity_types' => ['project'],
            'name_en'      => 'Project Approval',
            'name_fr'      => 'Approbation d\'un projet',
        ];
    }

    public function getStructure()
    {
        return collect([
            [
                'name_key' => 'gate-one-approval',
                'name_en'  => 'Gate 1 Approval',
                'name_fr'  => 'Approbation Jalon 1',
                'forms'    => [
                    [
                        'name_en' => 'Business Case',
                        'name_fr' => 'Plan d\'affaire',
                    ],
                    [
                        'name_en' => 'Planned Product List',
                        'name_fr' => 'Liste de produits prévus',
                    ],
                    [
                        'name_key'    => 'gate-one-approval',
                        'name_en'     => 'Gate 1 Approval',
                        'name_fr'     => 'Approbation Jalon 1',
                        'assessments' => ['business-case', 'planned-product-list'],
                    ],
                ],
            ],
        ]);
    }

    public function getNotifications()
    {
        return collect([
            [
                'name_key' => 'project-ready-for-gate-one-approval',
                'name_en'  => 'Project Ready for Gate 1 Approval',
                'name_fr'  => 'Projet prêt pour Jalon 1',
            ],
            [
                'name_key' => 'business-case-rejected',
                'name_en'  => 'Business Case Requires Adjustments',
                'name_fr'  => 'Plan d\'affaire nécessite des ajustements',
            ],
            [
                'name_key' => 'planned-product-list-rejected',
                'name_en'  => 'Planned Product List Requires Adjustments',
                'name_fr'  => 'Liste de produits planifiés nécessite des ajustements',
            ],
            [
                'name_key' => 'project-cancelled',
                'name_en'  => 'Project Cancelled',
                'name_fr'  => 'Projet annulé',
            ],
            [
                'name_key' => 'project-approved',
                'name_en'  => 'Project Approved',
                'name_fr'  => 'Projet approuvé',
            ],
        ]);
    }

    public function getInformationSheets()
    {
        return collect([
            [
                'name_key' => 'gate-one-project-approval',
                'name_en'  => 'Gate 1 – Project Approval',
                'name_fr'  => 'Jalon 1 – Approbation de projet',
            ],
        ]);
    }

    public function authorize($project)
    {
        // Only start this process if learning product is still new.
        return $project->state->name_key == 'new';
    }
}
