<?php

return [
    'title' => 'Plan d’affaire',
    'tabs' => [
        'project_objective'              => 'Objectif du projet',
        'proposed_solution'              => 'Solution proposée',
        'school_priorities'              => 'Priorités de l’École',
        'target_audience'                => 'Public cible',
        'departmental_results_framework' => 'Cadre de résultats ministériels',
        'costs'                          => 'Coûts et ressources',
        'internal_resources'             => 'Ressources internes',
        'risk'                           => 'Risques',
        'comments'                       => 'Commentaires',
    ],
    'form_section_groups' => [
        'risk'     => 'Risque|Risques',
        'spending' => 'Ventilation des coûts|Ventilation des coûts',
    ],
    'business_issue' => [
        'label'       => 'Écarts de rendement ou problèmes opérationnels',
        'instruction' => 'Veuillez décrire les écarts de rendement ou les problèmes opérationnels que l’École souhaite résoudre.',
        'description' => 'Les écarts de rendement et les problèmes opérationnels motivant ce projet.',
        'help'        => 'Par exemple : Une communauté fonctionnelle a relevé une lacune d’apprentissage cruciale qui n’est pas comblée par l’offre actuelle de l’École.',
    ],
    'short_term_learning_response' => [
        'label'       => 'Stratégie de réponse à court terme (0 à 3 mois)',
        'instruction' => 'Veuillez indiquer les livrables pour les 3 prochains mois suivant l’approbation du projet.',
        'description' => 'Stratégie de réponse d’apprentissage à court terme (0 à 3 mois).',
        'help'        => 'Par exemple : l’accroissement des efforts marketing des produits existants et/ou des produits connexes, évènements, webémissions',
    ],
    'medium_term_learning_response' => [
        'label'       => 'Stratégie de réponse à moyen terme (3 à 6 mois)',
        'instruction' => 'Veuillez indiquer les livrables pour les 3 à 6 prochains mois suivant l’approbation du projet.',
        'description' => 'Stratégie de réponse d’apprentissage à moyen terme (3 à 6 mois).',
        'help'        => 'Par exemple : vidéos, micro-apprentissage, créer un nouveau produit à partir de produits connus (par ex : créer un module autonome à partir d’un cours existant)',
    ],
    'long_term_learning_response' => [
        'label'       => 'Stratégie de réponse à long terme (6 à 18 mois)',
        'instruction' => 'Veuillez indiquer les livrables pour les 6 à 18 prochains mois suivant l’approbation du projet.',
        'description' => 'Stratégie de réponse d’apprentissage à long terme (6 à 18 mois).',
        'help'        => 'Par exemple : cours en ligne ou en salle de classe qui seront ajoutés au programme de cours de l’École',
    ],
    'school_priorities' => [
        'label'       => 'Priorités de l’École',
        'instruction' => 'Veuillez sélectionner tous les choix qui s’appliquent à votre projet.',
        'description' => 'La liste des priorités de l’École adressé par ce projet.',
    ],
    'request_origins' => [
        'label'       => 'Origines de la demande',
        'instruction' => 'Pourquoi soumettez-vous ce projet?',
        'description' => 'Les circonstances ayant menées à la création ou la modification de ce projet.',
        'help'        => 'Par exemple : Y a-t-il eu un changement de politique qui exige une transformation des produits d’apprentissage? Y avait-il une nouvelle priorité du gouvernement du Canada à laquelle les produits d’apprentissage devaient répondre?',
    ],
    'request_origin_other' => [
        'label'       => 'Autres origines de la demande',
    ],
    'is_required_training' => [
        'label'       => 'Formation obligatoire / Fait partie des exigences d’une attestation',
        'instruction' => 'Veuillez indiquer si les produits d’apprentissage inclus dans le projet sont considérés comme étant une « formation obligatoire » et /ou « faisant partie des exigences d’une attestation ».',
        'description' => 'Les produits d’apprentissage inclus dans le projet sont considérés comme étant une formation « obligatoire » ou « faisant partie des exigences d’une attestation ».',
        'help'        => 'La définition de « formation obligatoire » se retrouve dans la politique du Conseil du trésor en matière d’apprentissage, de formation et de perfectionnement.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Nombre prévu de participants (par année)',
        'description' => 'Une estimation du nombre de participants par année.',
    ],
    'communities' => [
        'label'       => 'Communautés',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
        'description' => 'La liste des communautés et sous-communautés visées.',
    ],
    'departmental_results_framework_indicators' => [
        'label'       => 'Indicateurs',
        'instruction' => 'Veuillez sélectionner tous les indicateurs qui s’appliquent à ce projet.',
        'description' => 'Les indicateurs du Cadre de résultats ministériels adressés par le projet.',
    ],
    'cost_centre' => [
        'label'       => 'Centre de coûts',
        'instruction' => 'Veuillez fournir votre centre de coûts.',
        'description' => 'Le centre de coûts du projet.',
        'hint'        => 'Ex: Q12345',
    ],
    'spendings' => [
        'internal_resource' => [
            'label'       => 'Ressources internes',
            'description' => 'La liste des ressources internes de l’École qui pourraient être affectées par ce projet.',
            'help'        => 'Par exemple : Des ressources des Solutions d’apprentissage peuvent être nécessaires pour créer un cours en ligne ou une vidéo. De nouveaux membres devront peut-être être embauchés au sein du personnel enseignant pour donner un cours en salle de classe.',
        ],
        'cost_description' => [
            'label'       => 'Description des coûts',
            'description' => 'La description détaillée des coûts.',
            'help'        => 'Par exemple : Le montant reflète le salaire d’un nouveau membre du personnel enseignant. Le montant correspond au coût associé à la traduction et au montage d’une vidéo.',
        ],
        'cost' => [
            'label'       => 'Coût',
            'description' => 'Estimation des coûts requis.',
            'help'        => 'Par exemple : 85 000 $ pour le salaire estimé d’un expert en la matière. 4 000 $ pour la traduction',
        ],
        'recurrence' => [
            'label'       => 'Récurrence',
            'description' => 'La récurrence du coût.',
            'help'        => 'Par exemple : Le coût est-il un investissement initial ou sera-t-il récurrent annuellement?',
        ],
        'comments' => [
            'label'       => 'Commentaires',
            'instruction' => 'Veuillez ajouter tout renseignement pertinent relatif au coût ou à la ressource saisie ci-dessus.',
            'description' => 'Tout autre renseignement pertinent sur ce coût ou cette ressource.',
            'help'        => 'Par exemple : L’utilisation de deux ressources au sein de l’équipe des Solutions d’apprentissage représente 40 % de la main-d’œuvre actuellement disponible et peut avoir une incidence sur d’autres projets de l’École.',
        ],
    ],
    'other_operational_considerations' => [
        'label'       => 'Autres considérations opérationnelles',
        'instruction' => 'Is there an additional impact on costs or resources?',
        'description' => 'Tout autre considération opérationnelle pertinente concernant les coûts ou les ressources.',
    ],
    'risks' => [
        'risk_type' => [
            'label'       => 'Risque',
            'instruction' => 'Veuillez indiquer tous les risques pour l’École si ce projet n’est pas approuvé.',
            'description' => 'La description de tout risque, enjeu ou contrainte connus touchant le projet.',
        ],
        'risk_type_other' => [
            'label'       => 'Autre risque',
        ],
        'risk_rationale' => [
            'label'       => 'Justification',
            'instruction' => 'Veuillez indiquer pourquoi il s’agit d’un risque.',
            'description' => 'L’impact opérationnel sur l’École ou le Gouvernement du Canada si ce projet n’est pas réalisé.',
            'help'        => 'Par exemple : Les spécialistes en AIPRP seraient incapable d’accomplir leurs tâches car la formation obligatoire détient du contenu désuet.',
        ],
    ],
    'comments' => [
        'label'       => 'Commentaires généraux',
        'description' => 'Tout autre renseignement pertinent relatif au plan d’affaire.',
    ],
];
