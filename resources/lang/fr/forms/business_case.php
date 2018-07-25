<?php

return [
    'tabs' => [
        'business_drivers'      => 'Incitatifs administratifs',
        'proposal'              => 'Proposition',
        'timeframe'             => 'Échéancier',
        'audience'              => 'Public cible',
        'departmental_benefit'  => 'Avantages pour le ministère',
        'learners_benefit'      => 'Avantages pour l\'apprenant',
        'costs'                 => 'Coûts',
        'internal_resources'    => 'Ressources internes',
    ],
    'business_issue' => [
        'label'       => 'Problèmes opérationnels',
        'instruction' => 'Veuillez décrire les lacunes en matière de rendement ou les problèmes opérationnels qui sont résolus par ce projet.',
        'description' => 'Les problèmes opérationnels motivant ce projet.',
        'help'        => 'Quels sont les enjeux opérationnels, problèmes ou lacunes en matière de rendement que l’École tente de régler?',
    ],
    'government_priorities' => [
        'label'       => 'Priorités du programme de base du gouvernement',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
        'description' => 'La liste des priorités du programme de base du gouvernement visées par ce projet.',
    ],
    'request_sources' => [
        'label'       => 'Source de la demande',
        'description' => 'La source de la demande.',
    ],
    'potential_solution_types' => [
        'label'       => 'Types de solutions potentielles',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
        'description' => 'La liste des types de solutions potentielles pour résoudre le problème opérationnel.',
        'help'        => '
            <ul>
                <li><span>Solution commerciale</span> : matériel d\'un tiers exigeant un achat, par exemple, Skillsoft.</li>
            </ul>',
    ],
    'learning_response_strategy' => [
        'label'       => 'Stratégie en matière d’apprentissage',
        'instruction' => 'Veuillez fournir un aperçu général de votre projet et de la manière dont il permet de régler les lacunes et les problèmes décrits ci-dessus.',
        'description' => 'La description de la stratégie en matière d’apprentissage proposée.',
    ],
    'is_required_training' => [
        'label'       => 'Formation obligatiore / Fait partie des exigences d\'une attestation',
        'instruction' => 'Veuillez indiquer si les produits d’apprentissage inclus dans le projet sont considérés comme étant une formation « obligatoire » (en vertu d’une politique) ou « faisant partie des exigences d\'une attestation ».',
        'description' => 'Indique si les produits d’apprentissage inclus dans le projet sont considérés comme étant une formation « obligatoire » ou « faisant partie des exigences d\'une attestation ».',
    ],
    'timeframe' => [
        'label'       => 'Calendrier prévu',
        'description' => 'Le calendrier prévu du projet.',
        'help'        => 'Le calendrier cible jusqu’au lancement des derniers produits inclus.',
    ],
    'timeframe_rationale' => [
        'label'       => 'Justification du calendrier prévu',
        'instruction' => 'Veuillez expliquer le calendrier prévu ci-dessus.',
        'description' => 'La justification du calendrier prévu du projet.',
    ],
    'communities' => [
        'label'       => 'Communautés',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
        'description' => 'La liste des communautés et sous-communautés visées.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Nombre prévu de participants (par année)',
        'description' => 'Une estimation du nombre de participants par année.',
    ],
    'departmental_benefit_type' => [
        'label'       => 'Type',
        'description' => 'Le type d’avantage pour le ministère.',
    ],
    'departmental_benefit_rationale' => [
        'label'       => 'Justification',
        'description' => 'La justification de l’avantage pour le ministère.',
    ],
    'learners_benefit_type' => [
        'label'       => 'Type',
        'description' => 'Le type d’avantage pour l’apprenant.',
    ],
    'learners_benefit_rationale' => [
        'label'       => 'Justification',
        'description' => 'La justification de l’avantage pour l’apprenant.',
    ],
    'cost_center' => [
        'label'       => 'Centre de coûts',
        'instruction' => 'Veuillez fournir votre centre de coûts.',
        'description' => 'Le centre de coûts du projet.',
        'hint'        => 'Ex: Q12345',
    ],
    'general_ledger_account' => [
        'label'       => 'Compte du grand livre général',
        'instruction' => 'Veuillez fournir votre compte du grand livre général.',
        'description' => 'Le compte du grand livre général du projet.',
        'hint'        => 'Ex: 12345',
    ],
    'maintenance_fund' => [
        'label'       => 'Estimation des fonds supplémentaires requis pour le fonctionnement et l\'entretien (F et E)',
        'description' => 'L’estimation des fonds supplémentaires requis pour le fonctionnement et l\'entretien.',
        'help'        => 'Également appelé « fonds non salariaux ».',
    ],
    'maintenance_fund_rationale' => [
        'label'       => 'Justification des fonds supplémentaires requis pour le fonctionnement et l\'entretien (F et E)',
        'instruction' => 'Veuillez fournir une description et une justification des fonds supplémentaires requis pour le fonctionnement et l\'entretien.',
        'description' => 'La description et la justification des fonds supplémentaires requis pour le fonctionnement et l\'entretien.',
    ],
    'salary_fund' => [
        'label'       => 'Estimation des fonds salariaux supplémentaires requis',
        'description' => 'L’estimation des fonds salariaux supplémentaires requis.',
    ],
    'salary_fund_rationale' => [
        'label'       => 'Justification des fonds salariaux supplémentaires',
        'instruction' => 'Veuillez fournir une description et une justification des fonds salariaux supplémentaires.',
        'description' => 'La description et la justification des fonds salariaux supplémentaires requis.',
    ],
    'internal_resources' => [
        'label'       => 'Ressources internes requises',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
        'description' => 'Une liste des ressources ministérielles qui seront requises pour ce projet.',
        'help'        => '
            <ul>
                <li><span>Corps professoral</span> : Cette équipe travaille à l’intégration et à la gestion de ses membres. P. ex. : formateurs.</li>
                <li><span>A/V</span> : Cette équipe prépare les salles pour les cours, événements, etc.</li>
                <li><span>PON</span> : Cette équipe aide à planifier les opérations au niveau national tel que les offres de cours ou les événements.</li>
            </ul>

        '
    ],
];
