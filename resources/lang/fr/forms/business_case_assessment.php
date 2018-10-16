<?php

return [
    'title' => 'Évaluation du plan d\'affaire',
    'tabs' => [
        'assessment' => 'Évaluation',
        // form tabs ommited, since they are dynamically added by the entity title
    ],
    'is_process_cancelled' => [
        'label'       => 'Annulation du projet',
        'option'      => 'J\'aimerai annuler ce projet.',
        'description' => 'Indique si le projet a été annulé ou non.',
    ],
    'process_cancellation_rationale' => [
        'label'       => 'Justification de l\'annulation',
        'instruction' => 'Veuillez fournir une explication si le projet est annulé.',
        'description' => 'La justification de l\'annulation du projet.',
    ],
    'process_form_decision_id' => [
        'label'       => 'Décision',
        'description' => 'La décision relative au plan d\'affaire.',
    ],
    'assessment_comments' => [
        'label'       => 'Commentaires',
        'instruction' => 'Veuillez fournir une explication si des ajustements sont nécessaires.',
        'description' => 'Les commentaires appuyant la décision relative au plan d\'affaire.',
    ],
    'assessment_date' => [
        'label'       => 'Date de l\'évaluation',
        'description' => 'La date à laquelle l\'évaluation a été faite.',
    ]
];
