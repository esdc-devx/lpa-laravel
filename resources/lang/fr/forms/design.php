<?php

return [
    'form_title' => 'Conception',
    'tabs'  => [
        'description'     => 'Description',
        'specifications'  => 'Spécifications',
        'content'         => 'Contenu',
        'classifications' => 'Classifications',
        'prerequisites'   => 'Préalables',
        'clients'         => 'Clients',
        'timeframe'       => 'Échéancier',
        'costs'           => 'Coûts',
        'comments'        => 'Commentaires',
    ],
    'form_section_groups' => [
        'applicable_policy' => 'Politique applicable|Politiques applicables',
        'additional_cost'   => 'Coût additionel|Coûts additionels',
    ],
    'learning_product_code' => [
        'label'       => 'Code du produit d\'apprentissage',
        'description' => 'Le code du produit d’apprentissage.',
        'help'        => 'Anciennement connu sous le nom de "legacy course code". Tous les produits d\'apprentissage connexes ou intégrés doivent partager le même code de produit d\'apprentissage. Par exemple, toutes les vidéos et tous les outils de travail intégrés dans un cours donné doivent partager le même code de produit d\'apprentissage.',
        'instruction' => 'Pour les nouveaux produits d\'apprentissage, veuillez contacter les administrateurs de l\'APA pour obtenir un nouveau code.',
    ],
    'sequence' => [
        'label'       => 'Séquence',
        'description' => 'Le numéro de séquence du produit d\'apprentissage à l\'intérieur du code de produit d\'apprentissage.',
        'help'        => 'Par exemple, chaque vidéo à l\'intérieur du code du produit d\'apprentissage P999 se verra attribué un numéro de séquence unique (1, 2, 3…)',
        'instruction' => 'Veuillez attribuer un numéro de séquence unique pour chaque produit d\'apprentissage qui partage le même code de produit d\'apprentissage.',
    ],
    'version' => [
        'label'       => 'Version',
        'description' => 'La version du produit d\'apprentissage.',
    ],
    'description' => [
        'label'       => 'Description',
        'description' => 'La description du produit d\'apprentissage.',
        'help'        => 'Il s’agit de la description interne du produit d’apprentissage. Elle contribuera à la création de la description officielle dans le SHGA et sur GCcampus.',
    ],
    'learning_objectives' => [
        'label'       => 'Objectifs d’apprentissage',
        'description' => 'Les objectifs d’apprentissage de ce produit.',
        'instruction' => 'Veuillez indiquer les objectifs du produit d\'apprentissage. ',
    ],
    'target_audience_description' => [
        'label'       => 'Description du public cible',
        'description' => 'La description du public cible du produit d\'apprentissage.',
        'help'        => 'Cette section aidera le Centre de service à la clientèle à aider les apprenants à choisir leurs produits d\'apprentissage.',
    ],
    'outcome_types' => [
        'label'       => 'Résultats d’apprentissage',
        'description' => 'Les types de résultats d’apprentissage.',
        'instruction' => 'Veuillez identifier les types de résultat d\'apprentissage du produit d\'apprentissage.',
    ],
    'is_required_training' => [
        'label'       => 'Formation obligatoire / Fait partie des exigences d\'une attestation',
        'description' => 'Si le produit d’apprentissage est considéré ou non comme étant une « formation obligatoire » ou « faisant partie des exigences d\'une attestation ».',
        'help'        => 'La définition de «formation obligatoire » se retrouve dans la politique du Conseil du trésor en matière d’apprentissage, de formation et de perfectionnement.',
        'instruction' => 'Veuillez indiquer si le produit d’apprentissage est considéré comme étant une «formation obligatoire » et /ou « faisant partie des exigences d\'une attestation ».',
    ],
    'total_duration' => [
        'label'       => 'Durée totale',
        'description' => 'La durée totale du produit d’apprentissage, en heures.',
        'help'        => 'Par exemple: 1 journée = 7,5 heures.',
        'instruction' => 'Veuillez saisir la durée totale du produit d\'apprentissage, en heures.',
    ],
    'possible_offering_types' => [
        'label'       => 'Types d\'offre possibles',
        'description' => 'Les types d\'offre possibles.',
        'help'        => '
            <ul>
                <li><span>En personne</span> : Tous les participants doivent assister à partir de l\'un des lieux officiels du cours/événement.</li>
                <li><span>Virtuelle</span> : Tous les participants doivent assister à l\'aide d\'une plateforme de communication bidirectionnelle tel que WebEx.</li>
                <li><span>En personne et virtuelle simultanément</span> : Certains participants peuvent assister en personne alors que d\'autres peuvent participer à distance.</li>
            </ul>
        ',
        'instruction' => 'Veuillez identifier les types d\'offre possibles pour ce produit d\'apprentissage.',
    ],
    'registration_mode' => [
        'label'       => 'Mode d’inscription',
        'description' => 'Les modes d’inscription offerts aux participants.',
        'help'        => '
            <ul>
                <li><span>Volontaire</span> : Les participants peuvent s’inscrire directement sur GCcampus.</li>
                <li><span>Désigné</span> : Les participants peuvent uniquement être inscrits par leur coordonnateur de la formation.</li>
            </ul>
        ',
        'instruction' => 'Veuillez identifier le mode d\'inscription au produit d\'apprentissage.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Nombre prévu de participants (par année)',
        'description' => 'Une estimation du nombre de participants par année.',
    ],
    'applicable_policies' => [
        'applicable_policy' => [
            'label'       => 'Politiques applicables',
            'description' => 'Liste des politiques liées au produit d\'apprentissage.',
            'instruction' => 'Veuillez saisir la liste des politiques qui influencent le contenu du produit d\'apprentissage.',
        ]
    ],
    'content_source_type' => [
        'label'       => 'Source du contenu',
        'description' => 'La source du contenu du produit d\'apprentissage.',
        'help'        => '
            <ul>
                <li><span>Nouveau</span> : N’existait pas auparavant.</li>
                <li><span>Remplacement</span> : Tiré d’un seul cours actif ou archivé.</li>
                <li><span>Fractionné</span> : Tiré d’un cours actif ou archivé et séparé en deux nouveaux cours ou plus, y compris celui-ci. </li>
                <li><span>Fusionné</span> : Tiré de deux cours actifs ou archivés ou plus, et fusionné en un seul cours (celui-ci).</li>
                <li><span>Restructuré</span> : Tiré de deux cours actifs ou archivés ou plus, et restructuré en deux cours ou plus, y compris celui-ci.</li>
            </ul>
        ',
        'instruction' => 'Sélectionnez la situation qui représente le mieux le contenu du produit d\'apprentissage.',
    ],
    'content_source_codes' => [
        'label'       => 'Produits d\'origine',
        'description' => 'La liste des codes de produits d\'apprentissage actifs ou archivés qui ont contribué au contenu du produit d\'apprentissage actuel.',
        'instruction' => 'Veuillez saisir une liste de codes de produits d\'apprentisage.',
    ],
    'topics' => [
        'label'       => 'Sujets',
        'description' => 'La liste des sujets traités par ce produit d’apprentissage.',
        'help'        => 'Également appelés « volets du programme ».',
        'instruction' => 'Veuillez sélectionner tous les choix applicables.',
    ],
    'programs' => [
        'label'       => 'Programmes',
        'description' => 'La liste des programmes d’apprentissage correspondants.',
    ],
    'series' => [
        'label'       => 'Séries',
        'description' => 'La liste de séries d’apprentissages correspondantes.',
    ],
    'curriculum_type' => [
        'label'       => 'Type de programme d’étude',
        'description' => 'Le type de programme d’étude du produit d’apprentissage.',
    ],
    'management_accountability_framework_areas' => [
        'label'       => 'Sections du Cadre de responsabilisation de gestion traitées',
        'description' => 'Les sections du Cadre de responsabilisation de gestion (CRG) traitées par ce produit d’apprentissage.',
    ],
    'competencies' => [
        'label'       => 'Compétences abordées',
        'description' => 'La liste des compétences abordées dans ce produit d’apprentissage.',
        'help'        => 'Également appelé « lacune en matière de rendement ».',
        'instruction' => 'Veuillez sélectionner trois options au maximum.',
    ],
    'target_audience_knowledge_level' => [
        'label'       => 'Niveau de connaissances des apprenants',
        'description' => 'Une description du niveau de connaissances du public cible.',
        'instruction' => 'Veuillez identifier le niveau des connaissances des apprenants.',
    ],
    'communities' => [
        'label'       => 'Communautés',
        'description' => 'La liste des communautés et sous-communautés ciblées par ce produit d’apprentissage.',
        'instruction' => 'Veuillez sélectionner toutes les options applicables.',
    ],
    'mandatory_prerequisites' => [
        'label'       => 'Produits d\'apprentissage préalables obligatoires',
        'description' => 'La liste des codes de produits d\'apprentissage préalables obligatoires.',
        'instruction' => 'Veuillez saisir la liste des codes de produit d\'apprentissage.',
    ],
    'recommended_prerequisites' => [
        'label'       => 'Produits d’apprentissage préalables recommandés',
        'description' => 'La liste des codes de produits d\'apprentissage préalables recommandés.',
        'instruction' => 'Veuillez saisir la liste des codes de produit d\'apprentissage.',
    ],
    'complementary_learning_products' => [
        'label'       => 'Produits d’apprentissage complémentaires',
        'description' => 'La liste des codes de produits d\'apprentissage complémentaires.',
        'instruction' => 'Veuillez saisir la liste des codes de produit d\'apprentissage.',
    ],
    'prescribed_by_tbs' => [
        'label'       => 'Demandé par le Conseil du trésor',
        'description' => 'La description de l’autorité qui demande ce produit d’apprentissage.',
        'help'        => '
            <ul>
                <li><span>Secrétariat du Conseil du Trésor du Canada</span> :  Aussi appelé "Requis pour tous les ministères".</li>
                <li><span>Un ou plusieurs ministères</span> : Aussi appelé "Obligatoire pour des ministères particuliers".</li>
            </ul>
        ',
    ],
    'prescribed_by_departments' => [
        'label'       => 'Ministères demandeurs',
        'description' => 'La liste de tous les ministères, organismes, sociétés d’État et organismes de service spéciaux du gouvernement du Canada qui demandent ce produit d’apprentissage.',
    ],
    'recommended_by_departments' => [
        'label'       => 'Ministères recommandant',
        'description' => 'La liste de tous les ministères, organismes, sociétés d’État et organismes de service spéciaux du gouvernement du Canada qui recommandent ce produit d’apprentissage.',
    ],
    'expected_pilot_start_date' => [
        'label'       => 'Date de début prévue du projet pilote',
        'description' => 'La date de début prévue du projet pilote.',
        'instruction' => 'Quelle est la date de début du projet pilote pour ce produit d\'apprentissage?',
    ],
    'expected_launch_date' => [
        'label'       => 'Date de lancement prévue',
        'description' => 'La date de lancement prévue.',
    ],
    'recommended_review_interval' => [
        'label'       => 'Intervalle recommandé entre les révisions (en mois)',
        'description' => 'Le nombre recommandé de mois entre chaque révision systématique du contenu de ce produit d’apprentissage.',
    ],
    'additional_costs' => [
        'rationale' => [
            'label'       => 'Justification',
            'description' => 'La justification des coûts additionnels, c\'est-à-dire les coûts excédant le budget établi pour le projet.',
            'help'        => 'Par exemple: La traduction du vidéo devra être faite à l\'externe pour respecter la date de lancement.',
            'instruction' => 'Pourquoi ce produit d\'apprentissage nécessitera un financement supplémentaire (en dehors du budget établi du projet)?',
        ],
        'costs' => [
            'label'       => 'Coûts',
            'description' => 'Estimation des coûts additionnels requis pour développer le produit d\'apprentissage.',
        ],
    ],
    'internal_order' => [
        'label'       => 'Commande interne',
        'description' => 'Le numéro de commande interne.',
        'hint'        => 'P. ex. : M123456',
    ],
    'comments' => [
        'label'       => 'Commentaires généraux',
        'description' => 'Tout autre renseignement pertinent relatif au produit d\'apprentissage.',
    ],
];
