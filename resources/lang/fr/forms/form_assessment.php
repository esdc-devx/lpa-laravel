<?php

return [
    'tabs' => [
        'overall_assessment' => 'Évaluation globale',
        // form tabs ommited, since they are dynamically added by the entity title
    ],
    'assessment_date' => [
        'label'       => 'Date de l\'évaluation',
        'description' => 'La date à laquelle l\'évaluation a été faite.',
    ],
    'process_form_decision_id' => [
        'label'       => 'Décision',
        'description' => 'La décision relative au formulaire :assessed_form_title.',
    ],
    'assessment_comments' => [
        'label'       => 'Commentaires',
        'instruction' => 'Veuillez fournir une explication si des ajustements sont nécessaires.',
        'description' => 'Les commentaires appuyant la décision relative au formulaire :assessed_form_title.',
    ],
    'entity_cancellation' => [
        'project' => [
            'cancel_entity'  => 'L\'annulation d\'un projet est une action critique et définitive. Cette action aura automatiquement lieu lors de la soumission de ce formulaire.',
            'is_entity_cancelled' => [
                'label'   => 'Évaluation du projet',
                'options' => [
                    'false'   => 'J’aimerais évaluer chaque formulaire individuellement.',
                    'true'    => 'J’aimerais annuler ce projet.',
                ],
                'description' => 'Indique si le projet a été annulé ou non.',
            ],
            'entity_cancellation_rationale' => [
                'label'       => 'Justification de l\'annulation',
                'instruction' => 'Veuillez fournir une explication si le projet est annulé.',
                'description' => 'La justification de l\'annulation du projet.',
            ],
        ],
        'course' => [
            'cancel_entity'       => 'L\'annulation d\'un cours est une action critique et définitive. Cette action aura automatiquement lieu lors de la soumission de ce formulaire.',
            'is_entity_cancelled' => [
                'label'   => 'Évaluation du cours',
                'options' => [
                    'false' => 'J’aimerais évaluer chaque formulaire individuellement.',
                    'true'  => 'J’aimerais annuler ce cours.',
                ],
                'description' => 'Indique si le cours a été annulé ou non.',
            ],
            'entity_cancellation_rationale' => [
                'label'       => 'Justification de l\'annulation',
                'instruction' => 'Veuillez fournir une explication si le cours est annulé.',
                'description' => 'La justification de l\'annulation du cours.',
            ],
        ],
    ]
];
