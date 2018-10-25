<?php
return [
    'title'               => 'Approbation Jalon 1',
    'is_entity_cancelled' => [
        'label'   => 'Évaluation du projet',
        'options' => [
            'J’aimerais évaluer chaque formulaire individuellement.',
            'J’aimerais annuler ce projet.',
        ],
        'description' => 'Indique si le projet a été annulé ou non.',
    ],
    'entity_cancellation_rationale' => [
        'label'       => 'Justification de l\'annulation',
        'instruction' => 'Veuillez fournir une explication si le projet est annulé.',
        'description' => 'La justification de l\'annulation du projet.',
    ],
];
