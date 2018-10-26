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
];
